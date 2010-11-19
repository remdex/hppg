<?php

$Image = false;
$cache = CSCacheAPC::getMem();  

// Display mode - album, lastupload
$mode = isset($Params['user_parameters_unordered']['mode']) ? $Params['user_parameters_unordered']['mode'] : 'album';


// Filters
$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';    
$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
$filterArray = array();    
if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
}
$filterArray['approved'] = 1;

$currentUser = erLhcoreClassUser::instance();

// Append cache key, depends on preview modes
$appendCacheKey = '';

// Lets cache the world :D
if ($mode == 'album') {
    
    // Avoids loading persistent object classes etc
    if (($albumID = $cache->getCacheVersion('album_id_by_pid'.(int)$Params['user_parameters']['image_id'])) === false){
        try {
            $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
        } catch (Exception $e){
            erLhcoreClassModule::redirect('/');
            exit;
        }
        $albumID = $Image->aid;
        $cache->store('album_id_by_pid'.(int)$Params['user_parameters']['image_id'],$albumID);
    }
    
    $sortModes = array(    
        'new'       => 'pid DESC',
        'newasc'        => 'pid ASC',            
        'popular'       => 'hits DESC, pid DESC',
        'popularasc'    => 'hits ASC, pid ASC',        
        'lasthits'      => 'mtime DESC, pid DESC',
        'lasthitsasc'   => 'mtime ASC, pid ASC',        
        'lastcommented' => 'comtime DESC, pid DESC',
        'lastcommentedasc' => 'comtime ASC, pid ASC',          
        'toprated'         => 'pic_rating DESC, votes DESC, pid DESC',
        'topratedasc'      => 'pic_rating ASC, votes ASC, pid ASC',    
        'lastrated'        => 'rtime DESC, pid DESC',
        'lastratedasc'     => 'rtime ASC, pid ASC'  
    );
    
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'new';
    $modeSQL = $sortModes[$modeSort]; 
     
    switch ($modeSort) {
    	case 'new':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'newasc':
    		$appendCacheKey = 'album_image_newasc_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'popular':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('album_'.$albumID).'_popular_'.$cache->getCacheVersion('most_popular_version',time(),1500).'_album_id_'.$albumID;
    	break;
    	
    	case 'popularasc':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('album_'.$albumID).'_popularasc_'.$cache->getCacheVersion('most_popular_version',time(),1500).'_album_id_'.$albumID;
    	break;
    	
    	case 'lasthits':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('album_'.$albumID).'_lasthits_'.$cache->getCacheVersion('last_hits_version',time(),600).'_album_id_'.$albumID;
    	break;
    	
    	case 'lasthitsasc':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('album_'.$albumID).'_lasthitsasc_'.$cache->getCacheVersion('last_hits_version',time(),600).'_album_id_'.$albumID;
    	break;
    	
    	case 'lastcommented':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('last_commented_'.$albumID).'_lastcommented_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'lastcommentedasc':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('last_commented_'.$albumID).'_lastcommentedasc_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'toprated':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('top_rated_'.$albumID).'_toprated_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'topratedasc':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('top_rated_'.$albumID).'_topratedasc_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'lastrated':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('last_rated_'.$albumID).'_lastrated_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
    	
    	case 'lastratedasc':
    		$appendCacheKey = 'album_image_'.$cache->getCacheVersion('last_rated_'.$albumID).'_lastratedasc_'.$cache->getCacheVersion('album_'.$albumID).'_album_id_'.$albumID;
    	break;
        	    	
    	default:
    		break;
    } 
    
} elseif ($mode == 'search') {
    $sortModes = array(    
        'new'               => '@id DESC',
        'newasc'            => '@id ASC',    
        'popular'           => 'hits DESC, @id DESC',
        'popularasc'        => 'hits ASC, @id ASC',          
        'lasthits'          => 'mtime DESC, @id DESC',
        'lasthitsasc'       => 'mtime ASC, @id ASC',        
        'lastcommented'     => 'comtime DESC, @id DESC',
        'lastcommentedasc'  => 'comtime ASC, @id ASC',          
        'lastrated'         => 'rtime DESC, @id DESC',
        'lastratedasc'      => 'rtime ASC, @id ASC',          
        'toprated'          => 'pic_rating DESC, votes DESC, @id DESC',
        'topratedasc'       => 'pic_rating ASC, votes ASC, @id ASC',
        'relevance'         => '@relevance DESC, @id DESC',
        'relevanceasc'      => '@relevance ASC, @id ASC'
    );
        
    // Because sphinx view already includes this filter
    unset($filterArray['approved']);
       
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'relevance';
    $modeSQL = $sortModes[$modeSort];
        
    switch ($modeSort) {
    	case 'new':
    		$appendCacheKey = 'album_searc_image_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'newasc':
    		$appendCacheKey = 'album_searc_image_newasc_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'popular':
    		$appendCacheKey = 'album_searc_image_popular_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'popularasc':
    		$appendCacheKey = 'album_searc_image_popularasc_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'lasthits':
    		$appendCacheKey = 'album_searc_image_lasthits_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'lasthitsasc':
    		$appendCacheKey = 'album_searc_image_lasthitsasc_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'lastcommented':
    		$appendCacheKey = 'album_searc_image_lastcommented_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'lastcommentedasc':
    		$appendCacheKey = 'album_searc_image_lastcommentedasc_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'toprated':
    		$appendCacheKey = 'album_searc_image_toprated_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'topratedasc':
    		$appendCacheKey = 'album_searc_image_topratedasc_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'lastrated':
    		$appendCacheKey = 'album_searc_image_lastrated_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
    	
    	case 'lastratedasc':
    		$appendCacheKey = 'album_searc_image_lastratedasc_'.urldecode($Params['user_parameters_unordered']['keyword']);
    	break;
        	    	
    	default:
    		break;
    }
    
    $appendCacheKey .= '_sphinx_cache_version_'.$cache->getCacheVersion('sphinx_cache_version');
    
} elseif ($mode == 'myfavorites') {
    
    $favouriteSession = erLhcoreClassModelGalleryMyfavoritesSession::getInstance();    
    $appendCacheKey = '_my_favorites_version_'.$cache->getCacheVersion('favorite_'.$favouriteSession->id).'_session_id_'.$favouriteSession->id;
    
} elseif ($mode == 'popularrecent') {
          
    $appendCacheKey = 'popularrecent_mode_image_ajax_pid_'.$cache->getCacheVersion('popularrecent_version',time(),600);      
 
} elseif ($mode == 'ratedrecent') {
  
    $appendCacheKey = 'ratedrecent_mode_image_ajax_pid_version_'.$cache->getCacheVersion('ratedrecent_version');
       
} elseif ($mode == 'lastuploads') {
        
    $appendCacheKey = 'lastuploads_mode_image_ajax_pid_version_'.$cache->getCacheVersion('last_uploads');
    
} elseif ($mode == 'lasthits') {
        
    $appendCacheKey = 'lasthits_mode_image_ajax_pid_version_'.$cache->getCacheVersion('last_hits_version',time(),600);
    
} elseif ($mode == 'popular') {
                   
    $appendCacheKey = 'popular_mode_image_ajax_pid_version_'.$cache->getCacheVersion('most_popular_version',time(),1500);
    
} elseif ($mode == 'lastcommented') {
        
    $appendCacheKey = 'lastcommented_mode_image_ajax_pid_version_'.$cache->getCacheVersion('last_commented');
    
} elseif ($mode == 'lastrated') {
            
    $appendCacheKey = 'lastrated_mode_image_ajax_pid_version_'.$cache->getCacheVersion('last_rated');
    
} elseif ($mode == 'toprated') {
     
    $appendCacheKey = 'toprated_mode_image_ajax_pid_version_'.$cache->getCacheVersion('top_rated');
}

// Will be refactored in the future.
if ($currentUser->isLogged()) {
    $appendCacheKey .= 'user_id_'.$currentUser->getUserID();
}


// Global image cache key
$cacheKeyImageView = md5('image_window_'.(int)$Params['user_parameters']['image_id'].'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray).'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.$appendCacheKey).'_comment_version_'.$cache->getCacheVersion('last_commented_image_version_'.(int)$Params['user_parameters']['image_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' || ($Result = $cache->restore($cacheKeyImageView)) === false)
{ 

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/image.tpl.php');

if (!($Image instanceof erLhcoreClassModelGalleryImage)){
    try {
        $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
    }
}


$CommentData = new erLhcoreClassModelGalleryComment();
$needSave = false;

if ($currentUser->isLogged()){
    $CommentData->msg_author = $currentUser->getUserData(true)->username;
} else {
    $CommentData->msg_author = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Guest_');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{      
    $nameField = 'captcha_'.$_SESSION[$_SERVER['REMOTE_ADDR']]['comment'];
    $definition = array(
        'Name' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),
        
        'CommentBody' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),   
            
        $nameField => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    // Catpcha field expires in 10 minutes
    if ( !$form->hasValidData( $nameField ) || $form->$nameField == '' || $form->$nameField < time()-10*60 )
    {
        $Errors[] =   erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Wrong captcha code!');
    } 
    
    $validUsername = false;
    if ( !$form->hasValidData( 'Name' ) || $form->Name == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Please enter nick!');
    } else {$CommentData->msg_author = $form->Name;$validUsername = true;}
    
    if ($validUsername == true && (($currentUser->isLogged() && $currentUser->getUserData()->username != $form->Name) || ($currentUser->isLogged() == false)) && erLhcoreClassUser::getUserCount(array('filter' => array('username' => $form->Name))) > 0){
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Sutch username is already taken!');
    }
    
    if ( !$form->hasValidData( 'CommentBody' ) || $form->CommentBody == '' || mb_strlen($form->CommentBody) > 500 || erLhcoreClassModelGalleryComment::isSpam($form->CommentBody))
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Please enter comment!');
    } else $CommentData->msg_body = $form->CommentBody;
    
    
    if (count($Errors) == 0)
    {  
        
        $CommentData->pid = $Image->pid;
        $CommentData->msg_date = date('Y-m-d H:i:s');
        $CommentData->msg_hdr_ip = $_SERVER['REMOTE_ADDR'];
        $CommentData->author_md5_id = md5($CommentData->msg_author);
 
        if ($currentUser->isLogged())
        {
            $CommentData->author_id = $currentUser->getUserID();
        }
         
        erLhcoreClassGallery::getSession()->save($CommentData);
        $CommentData = new erLhcoreClassModelGalleryComment();               
        $Image->comtime = time();
        $needSave = true;
        
        //Clear cache
        CSCacheAPC::getMem()->delete('comments_'.$Image->pid);
        CSCacheAPC::getMem()->increaseCacheVersion('last_commented');
        CSCacheAPC::getMem()->increaseCacheVersion('last_commented_'.$Image->aid);
        CSCacheAPC::getMem()->increaseCacheVersion('last_commented_image_version_'.$Image->pid);
        
        
        $tpl->set('commentStored',true);
             
    }  else {
         
        $tpl->set('commentErrArr',$Errors);
    }
    
} 

   

if ($mode == 'album')
{
    
    if ($modeSort == 'new') {                
        $imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'sort' => 'pid ASC','filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)));
        $page = ceil((erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true, 'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)))+1)/20);
        $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)));        
    } elseif ($modeSort == 'newasc') {        
        $imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'sort' => 'pid DESC','filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)));
        $page = ceil((erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)))+1)/20);
        $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'sort' => 'pid ASC','limit' => 5,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)));        
    } elseif ($modeSort == 'popular') {
        
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);   
                   
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (hits > :hits OR hits = :hits AND pid > :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':hits',$Image->hits);
            $stmt->bindValue( ':pid',$Image->pid);       
            $stmt->bindValue( ':aid',$Image->aid);
            
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }    
                          
            $stmt->execute();
            $photos = $stmt->fetchColumn(); 
                       
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                 
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits DESC, pid DESC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                           
    } elseif ($modeSort == 'popularasc') {
                       
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('hits DESC, pid DESC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (hits < :hits OR hits = :hits AND pid < :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':hits',$Image->hits);
            $stmt->bindValue( ':pid',$Image->pid);       
            $stmt->bindValue( ':aid',$Image->aid); 
            
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
                  
            $stmt->execute();
            $photos = $stmt->fetchColumn();                 
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );   
                      
    } elseif ($modeSort == 'lasthits') {
                
                
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (mtime > :mtime OR mtime = :mtime AND pid > :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':mtime',$Image->mtime);
            $stmt->bindValue( ':pid',$Image->pid);   
            $stmt->bindValue( ':aid',$Image->aid);
             
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
               
            $stmt->execute();  
            $photos = $stmt->fetchColumn();         
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                                    
    } elseif ($modeSort == 'lasthitsasc') {
        
              
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ( '.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (mtime < :mtime OR mtime = :mtime AND pid < :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':mtime',$Image->mtime);
            $stmt->bindValue( ':pid',$Image->pid);   
            $stmt->bindValue( ':aid',$Image->aid);
              
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
              
            $stmt->execute();  
            $photos = $stmt->fetchColumn();         
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
               
    } elseif ($modeSort == 'lastcommented') {
        
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('comtime ASC, pid ASC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (comtime > :comtime OR comtime = :comtime AND pid > :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':comtime',$Image->comtime);
            $stmt->bindValue( ':pid',$Image->pid);   
            $stmt->bindValue( ':aid',$Image->aid);
             
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
                   
            $stmt->execute();
            $photos = $stmt->fetchColumn();
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();     
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('comtime DESC, pid DESC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                     
                     
    } elseif ($modeSort == 'lastcommentedasc') {
                     
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('comtime DESC, pid DESC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (comtime < :comtime OR comtime = :comtime AND pid < :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':comtime',$Image->comtime);
            $stmt->bindValue( ':pid',$Image->pid);   
            $stmt->bindValue( ':aid',$Image->aid); 
            
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
               
            $stmt->execute();
            $photos = $stmt->fetchColumn();
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();     
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('comtime ASC, pid ASC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                   
               
    } elseif ($modeSort == 'lastrated') {
                
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('rtime ASC, pid ASC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (rtime > :rtime OR rtime = :rtime AND pid > :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':rtime',$Image->rtime);
            $stmt->bindValue( ':pid',$Image->pid);   
            $stmt->bindValue( ':aid',$Image->aid);
             
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
                   
            $stmt->execute();
            $photos = $stmt->fetchColumn();
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();     
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('rtime DESC, pid DESC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                                            
    } elseif ($modeSort == 'lastratedasc') {
                        
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('rtime DESC, pid DESC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (rtime < :rtime OR rtime = :rtime AND pid < :pid) AND aid = :aid '.$countSQL.' LIMIT 1');
            $stmt->bindValue( ':rtime',$Image->rtime);
            $stmt->bindValue( ':pid',$Image->pid);   
            $stmt->bindValue( ':aid',$Image->aid); 
            
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
               
            $stmt->execute();
            $photos = $stmt->fetchColumn();
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();     
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('rtime ASC, pid ASC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                       
               
    } elseif ($modeSort == 'toprated') {
                    
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
                    
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating ASC, votes ASC, pid ASC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );  
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid > :pid) AND aid = :aid '.$countSQL);
            $stmt->bindValue( ':pic_rating',$Image->pic_rating);       
            $stmt->bindValue( ':votes',$Image->votes);       
            $stmt->bindValue( ':pid',$Image->pid);
            $stmt->bindValue( ':aid',$Image->aid);
              
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
                 
            $stmt->execute();
            $photos = $stmt->fetchColumn();         
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();     
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating DESC, votes DESC, pid DESC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                     
               
    } elseif ($modeSort == 'topratedasc') {
                       
            $db = ezcDbInstance::get(); 
            $session = erLhcoreClassGallery::getSession();        
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();
            $countSQLArray = array();
            $countSQL = '';
            $filterSQLString = '';
            
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
                $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
            }
        
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            $countSQL =  ' AND '.implode(' AND ',$countSQLArray);
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating DESC, votes DESC, pid DESC')
            ->limit( 5 );
            $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );  
            
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (pic_rating < :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes < :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid < :pid) AND aid = :aid '.$countSQL);
            $stmt->bindValue( ':pic_rating',$Image->pic_rating);       
            $stmt->bindValue( ':votes',$Image->votes);       
            $stmt->bindValue( ':pid',$Image->pid);
            $stmt->bindValue( ':aid',$Image->aid);
            
            foreach ($filterArray as $field => $filterValue){
                $stmt->bindValue( ':'.$field,$filterValue);
            }
                   
            $stmt->execute();
            $photos = $stmt->fetchColumn();         
            $page = ceil(($photos+1)/20);
            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array();     
            foreach ($filterArray as $field => $filterValue){
                $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating ASC, votes ASC, pid ASC')
            ->limit( 5 );
            $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );                                           
    }    
    
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';
    $urlAppend = $modeSort != 'new' ? '/(sort)/'.$modeSort : '';             
    $urlAppend .= $appendResolutionMode;
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',$Image->album_path.$pageAppend.$urlAppend);   
    $tpl->setArray($imagesParams);           
    
} elseif ($mode == 'search') {
                    
    if ($modeSort == 'new') { 
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','Filter' => $filterArray, 'filtergt' => array('pid' => $Image->pid)),
            array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','Filter' => $filterArray,'filterlt' => array('pid' => $Image->pid-1))
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];
        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
                  
    } elseif ($modeSort == 'relevance') {  
           
        // this query cannot be used in AddQuery, because it's result is used  
        $relevanceCurrentImage = erLhcoreClassGallery::searchSphinx(array('relevance' => true, 'SearchLimit' => 1,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC','Filter' => array('@id' => $Image->pid)),false);
                  
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti(
            array (
                array('filtergt' => array('pid' => $Image->pid),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'),
                array('filtergt' => array('@weight' => $relevanceCurrentImage),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'),
                array('filterlt' => array('pid' => $Image->pid-1),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'),
                array('filterlt' => array('@weight' => $relevanceCurrentImage-1),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC')
            ),false
        );
        
        $totalPhotos = $resultSearch[0];
          
        if ($totalPhotos['total_found'] < 5) { // We have check is there any better matches images on left
            $totalPhotosHigher = $resultSearch[1];
            
            if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {                
                $totalPhotos['list'] = (array)$totalPhotos['list']+(array)$totalPhotosHigher['list'];
            } elseif ($totalPhotosHigher['total_found'] > 0) {
                $totalPhotos['list'] = $totalPhotosHigher['list'];
            }
            
            $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
        } else { 
            // Needed for return page calcaution
            $totalPhotosHigher = $resultSearch[1];
            $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
        }        
            
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                        
        $totalPhotos = $resultSearch[2];
                     
        if ($totalPhotos['total_found'] < 5) { // We have check is there any better matches images on left
            $totalPhotosHigher = $resultSearch[3];
                                 
            if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {                           
                $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
            } elseif ($totalPhotosHigher['total_found'] > 0) {
                $totalPhotos['list'] = $totalPhotosHigher['list'];
            }
            
            $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
        }
             
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();
                  
    } elseif ($modeSort == 'relevanceasc') {  
             
        $relevanceCurrentImage = erLhcoreClassGallery::searchSphinx(array('relevance' => true, 'SearchLimit' => 1,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC','Filter' => array('@id' => $Image->pid)),false);
                  
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti(
            array (
                array('filterlt' => array('pid' => $Image->pid-1),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'),
                array('filterlt' => array('@weight' => $relevanceCurrentImage-1),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'),
                array('filtergt' => array('pid' => $Image->pid),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'),
                array('filtergt' => array('@weight' => $relevanceCurrentImage),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC')
            ),false
        );
        
        
        $totalPhotos = $resultSearch[0];
        
        if ($totalPhotos['total_found'] < 5) { // We have check is there any better matches images on left
            $totalPhotosHigher = $resultSearch[1];
                        
            if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {
                $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
            } elseif ($totalPhotosHigher['total_found'] > 0) {
                $totalPhotos['list'] = $totalPhotosHigher['list'];
            }
            
            $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
        } else {
            // Needed for return page calcaution
            $totalPhotosHigher = $resultSearch[1];
            $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
        }
            
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                        
        $totalPhotos = $resultSearch[2];
                        
        if ($totalPhotos['total_found'] < 5) { // We have check is there any better matches images on left
            $totalPhotosHigher = $resultSearch[3];
            
            if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {
                $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
            } elseif ($totalPhotosHigher['total_found'] > 0) {
                $totalPhotos['list'] = $totalPhotosHigher['list'];
            }
            
            $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
        }
             
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array(); 
                          
                  
    } elseif ($modeSort == 'newasc') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','Filter' => $filterArray,'filterlt' => array('pid' => $Image->pid-1)),
            array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','Filter' => $filterArray,'filtergt' => array('pid' => $Image->pid))
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];
        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array(); 
            
            
    } elseif ($modeSort == 'popular') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC')
        ),false);
                
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();   
            
            
    } elseif ($modeSort == 'popularasc') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC')
        ),false);
                
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
             
    } elseif ($modeSort == 'lasthits') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array(); 
             
    } elseif ($modeSort == 'lasthitsasc') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];      
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();        
                   
    } elseif ($modeSort == 'lastcommented') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
    } elseif ($modeSort == 'lastcommentedasc') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array(); 
                          
    } elseif ($modeSort == 'lastrated') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (rtime > '.$Image->rtime.' OR (rtime = '.$Image->rtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'rtime ASC, @id ASC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (rtime < '.$Image->rtime.' OR (rtime = '.$Image->rtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'rtime DESC, @id DESC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
    } elseif ($modeSort == 'lastratedasc') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (rtime < '.$Image->rtime.' OR (rtime = '.$Image->rtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'rtime DESC, @id DESC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (rtime > '.$Image->rtime.' OR (rtime = '.$Image->rtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'rtime ASC, @id ASC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = $resultSearch[1];        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array(); 
                          
    } elseif ($modeSort == 'toprated') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC')
        ),false);
                
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                        
        $totalPhotos = $resultSearch[1];
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();   
                      
    } elseif ($modeSort == 'topratedasc') {
        
        $resultSearch = erLhcoreClassGallery::searchSphinxMulti( array (
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'),
            array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC')
        ),false);
        
        $totalPhotos = $resultSearch[0];
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                        
        $totalPhotos = $resultSearch[1];
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();             
    }
       
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = $modeSort != 'relevance' ? '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'].'/(sort)/'.$modeSort : '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'];             
    $urlAppend .= $appendResolutionMode;
    
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/search').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams); 
    
} elseif ($mode == 'myfavorites') {
       		
	$imagesLeftArray = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'sort' => 'pid ASC','filter' => array('session_id' => $favouriteSession->id),'filtergt' => array('pid' => $Image->pid)));
    $page = ceil((erLhcoreClassModelGalleryMyfavoritesImage::getImageCount(array('filter' => array('session_id' => $favouriteSession->id),'filtergt' => array('pid' => $Image->pid)))+1)/20);
    $imagesRightArray = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'filter' => array('session_id' => $favouriteSession->id),'filterlt' => array('pid' => $Image->pid)));        
    $imagesLeft = array();
    $imagesRight = array();
    
    foreach ($imagesLeftArray as $imageLeftItem)
    {
        $imageItem = $imageLeftItem->image;
                        
        if ($imageItem !== false) {
    	   $imagesLeft[] = $imageItem;
        }
    }
    
    foreach ($imagesRightArray as $imageRightItem)
    {
        $imageItem = $imageRightItem->image;
        
        if ($imageItem !== false) {
    	   $imagesRight[] = $imageRightItem->image;
        }
    }
            
	$imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/myfavorites'; 
           
    $tpl->set('urlAppend',$urlAppend);
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/myfavorites').$urlAppend.$pageAppend);
    $tpl->setArray($imagesParams);		
    
} elseif ($mode == 'popularrecent') {
               
	$db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
            
    $hitsRecent = erLhcoreClassModelGalleryPopular24::fetch($Image->pid);
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryPopular24' );
    
    $q->where( '('.$q->expr->gt( 'hits', $q->bindValue( $hitsRecent->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $hitsRecent->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('hits ASC, pid ASC')
    ->limit( 5 );
    $imagesLeftArray = $session->find( $q, 'erLhcoreClassModelGalleryPopular24' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryPopular24' );
    
    $q->where( '('.$q->expr->lt( 'hits', $q->bindValue( $hitsRecent->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $hitsRecent->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('hits DESC, pid DESC')
    ->limit( 5 );
    $imagesRightArray = $session->find( $q, 'erLhcoreClassModelGalleryPopular24' );
            
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_popular24 WHERE (hits > :hits OR hits = :hits AND pid > :pid) LIMIT 1');
    $stmt->bindValue( ':hits',$hitsRecent->hits);
    $stmt->bindValue( ':pid',$Image->pid); 
                  
    $stmt->execute();
    $photos = $stmt->fetchColumn(); 
	           
    $page = ceil(($photos+1)/20);

    $imagesLeft = $imagesRight = array();
  
    foreach ($imagesLeftArray as $imageLeftItem)
    {
    	$imagesLeft[] = $imageLeftItem->image;
    }
    
    foreach ($imagesRightArray as $imageRightItem)
    {
    	$imagesRight[] = $imageRightItem->image;
    }
    
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/popularrecent';             
    $urlAppend .= $appendResolutionMode;

    $tpl->set('urlAppend',$urlAppend);
    $tpl->set('urlReturnToThumbnails', erLhcoreClassDesign::baseurl('gallery/popularrecent').$urlAppend.$pageAppend);
    $tpl->setArray($imagesParams);
    	
} elseif ($mode == 'ratedrecent') {
                        
    $ratedRecent = erLhcoreClassModelGalleryRated24::fetch($Image->pid);
            
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryRated24' );
           
    $q->where( '('.$q->expr->gt( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $ratedRecent->votes ) ).' OR '.
    $q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $ratedRecent->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $ratedRecent->pid ) ).') ')
    ->orderBy('pic_rating ASC, votes ASC, pid ASC')
    ->limit( 5 );
    $imagesLeftArray = $session->find( $q, 'erLhcoreClassModelGalleryRated24' ); 
       
    
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryRated24' );
                     
    $q->where( '('.$q->expr->lt( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $ratedRecent->votes ) ).' OR '.
    $q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $ratedRecent->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $ratedRecent->pid ) ).') ')
    ->orderBy('pic_rating DESC, votes DESC, pid DESC')
    ->limit( 5 );
    $imagesRightArray = $session->find( $q, 'erLhcoreClassModelGalleryRated24' );
           
   $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_rated24 WHERE (pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_rated24.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_rated24.votes = :votes AND pid > :pid) ');
   $stmt->bindValue( ':pic_rating',$ratedRecent->pic_rating);       
   $stmt->bindValue( ':votes',$ratedRecent->votes);       
   $stmt->bindValue( ':pid',$ratedRecent->pid);
               
   $stmt->execute();
   $photos = $stmt->fetchColumn();         
   $page = ceil(($photos+1)/20);

   
   $imagesLeft = $imagesRight = array();
  
   foreach ($imagesLeftArray as $imageLeftItem)
   {
   	    $imagesLeft[] = $imageLeftItem->image;
   }
   
   foreach ($imagesRightArray as $imageRightItem)
   {
   	    $imagesRight[] = $imageRightItem->image;
   }
                
   $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
   $pageAppend = $page > 1 ? '/(page)/'.$page : '';
   $urlAppend = '/(mode)/ratedrecent';
   $urlAppend .= $appendResolutionMode;

   $tpl->set('urlAppend',$urlAppend);
   $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/ratedrecent').$urlAppend.$pageAppend);
   $tpl->setArray($imagesParams);

} elseif ($mode == 'lastuploads') {
          
    $imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'filter' => $filterArray,'sort' => 'pid ASC','filtergt' => array('pid' => $Image->pid)));    	
    $page = ceil((erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true,'filtergt' => array('pid' => $Image->pid),'filter' => $filterArray))+1)/20);
    $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'limit' => 5,'filter' => $filterArray,'sort' => 'pid DESC','filterlt' => array('pid' => $Image->pid)));
     	
	$imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lastuploads'; 
    $urlAppend .= $appendResolutionMode;
                   
	$tpl->set('urlAppend',$urlAppend);
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/lastuploads').$urlAppend.$pageAppend);
    $tpl->setArray($imagesParams);
    
} elseif ($mode == 'lasthits') {
   
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();
    $countSQLArray = array();
    $countSQL = '';
    $filterSQLString = '';
    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
        $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
    }

    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    $countSQL = ' AND '.implode(' AND ',$countSQLArray);
         
    $q->where( $filterSQLString.' ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('mtime ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
              
    
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
    }
    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
        
    $q->where( $filterSQLString.'('.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('mtime DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (mtime > :mtime OR mtime = :mtime AND pid > :pid) '.$countSQL.' LIMIT 1');
    $stmt->bindValue( ':mtime',$Image->mtime);
    $stmt->bindValue( ':pid',$Image->pid);  

    foreach ($filterArray as $field => $filterValue){
            $stmt->bindValue( ':'.$field,$filterValue);
    }
        
    $stmt->execute();  
    $photos = $stmt->fetchColumn();         
    $page = ceil(($photos+1)/20);
    
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lasthits';             
    $urlAppend .= $appendResolutionMode; 
              
    $tpl->set('urlAppend',$urlAppend);
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/lasthits').$urlAppend.$pageAppend);
    $tpl->setArray($imagesParams);
    
	
} elseif ($mode == 'popular') {
       
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();
    $countSQLArray = array();
    $countSQL = '';
    $filterSQLString = '';
    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
        $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
    }

    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    $countSQL = ' AND '.implode(' AND ',$countSQLArray);
    
    $q->where( $filterSQLString.'('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('hits ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
    }
    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
        
    $q->where( $filterSQLString.'('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('hits DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (hits > :hits OR hits = :hits AND pid > :pid) '.$countSQL.' LIMIT 1');
    $stmt->bindValue( ':hits',$Image->hits);
    $stmt->bindValue( ':pid',$Image->pid); 
      
    foreach ($filterArray as $field => $filterValue){
            $stmt->bindValue( ':'.$field,$filterValue);
    }
        
    $stmt->execute();
    $photos = $stmt->fetchColumn(); 
	           
    $page = ceil(($photos+1)/20);
            
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/popular';             
    $urlAppend .= $appendResolutionMode;
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/popular').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);         
      	
} elseif ($mode == 'lastcommented') {
        
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();
    $countSQLArray = array();
    $countSQL = '';
    $filterSQLString = '';
    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
        $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
    }

    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    $countSQL = ' AND '.implode(' AND ',$countSQLArray);
    
    $q->where( $filterSQLString.'('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('comtime ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
    }
    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    
    $q->where( $filterSQLString.'('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('comtime DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (comtime > :comtime OR comtime = :comtime AND pid > :pid) '.$countSQL.' LIMIT 1');
    $stmt->bindValue( ':comtime',$Image->comtime);
    $stmt->bindValue( ':pid',$Image->pid);     
               
    foreach ($filterArray as $field => $filterValue){
            $stmt->bindValue( ':'.$field,$filterValue);
    }
     
    $stmt->execute();
          
    $photos = $stmt->fetchColumn();
    $page = ceil(($photos+1)/20);
         
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lastcommented';             
    $urlAppend .= $appendResolutionMode;
               
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/lastcommented').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);        
    
} elseif ($mode == 'lastrated') {
       
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();
    $countSQLArray = array();
    $countSQL = '';
    $filterSQLString = '';
    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
        $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
    }

    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    $countSQL = ' AND '.implode(' AND ',$countSQLArray);
    
    $q->where( $filterSQLString.'('.$q->expr->gt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('rtime ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
    }
    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    
    $q->where( $filterSQLString.'('.$q->expr->lt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
    ->orderBy('rtime DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (rtime > :rtime OR rtime = :rtime AND pid > :pid) '.$countSQL.' LIMIT 1');
    $stmt->bindValue( ':rtime',$Image->rtime);
    $stmt->bindValue( ':pid',$Image->pid);     
               
    foreach ($filterArray as $field => $filterValue){
            $stmt->bindValue( ':'.$field,$filterValue);
    }
     
    $stmt->execute();
          
    $photos = $stmt->fetchColumn();
    $page = ceil(($photos+1)/20);
         
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lastrated';             
    $urlAppend .= $appendResolutionMode;
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails', erLhcoreClassDesign::baseurl('gallery/lastrated').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);        
    
} elseif ($mode == 'toprated') {
       
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();
    $countSQLArray = array();
    $countSQL = '';
    $filterSQLString = '';
    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
        $countSQLArray[] = "lh_gallery_images.{$field} = :$field";
    }

    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
    $countSQL = ' AND '.implode(' AND ',$countSQLArray);    
    
    $q->where( $filterSQLString.'('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.
    $q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).') ')
    ->orderBy('pic_rating ASC, votes ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    
    $filterSQLArray = array();    
    foreach ($filterArray as $field => $filterValue){
        $filterSQLArray[] = $q->expr->eq( $field, $filterValue  );
    }
    $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
        
    $q->where( $filterSQLString.'('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.
    $q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).') ')
    ->orderBy('pic_rating DESC, votes DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
           
   $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid > :pid) '.$countSQL);
   $stmt->bindValue( ':pic_rating',$Image->pic_rating);       
   $stmt->bindValue( ':votes',$Image->votes);       
   $stmt->bindValue( ':pid',$Image->pid);
   
   foreach ($filterArray as $field => $filterValue){
            $stmt->bindValue( ':'.$field,$filterValue);
   }
   
   $stmt->execute();
   $photos = $stmt->fetchColumn();         
   $page = ceil(($photos+1)/20);
           
   $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
   $pageAppend = $page > 1 ? '/(page)/'.$page : '';
   $urlAppend = '/(mode)/toprated';
   $urlAppend .= $appendResolutionMode;
                 
   $tpl->set('urlAppend',$urlAppend);       
   $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/toprated').$urlAppend.$pageAppend);   
   $tpl->setArray($imagesParams);      
}

$tpl->set('mode',$mode);
$tpl->set('keyword',isset($Params['user_parameters_unordered']['keyword']) ? urldecode($Params['user_parameters_unordered']['keyword']) : '');

$tpl->set('image',$Image);
$tpl->set('comment_new',$CommentData);

$Result['content'] = $tpl->fetch();
$Result['path'] = $Image->path;
$Result['canonical'] = 'http://'.$_SERVER['HTTP_HOST'].$Image->url_path;

// Must be in the bottom, three options fo image hit.
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'delay_image_hit_enabled' ) == true) {
	erLhcoreClassModelGalleryDelayImageHit::addHit($Image->pid);
} elseif (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'delay_image_hit_log' ) == false) {
	$Image->hits++;
	$Image->mtime = time();
	erLhcoreClassGallery::getSession()->update($Image);
	$needSave = false;
}

if ($needSave == true) { // If comment was stored we need to update image
	erLhcoreClassGallery::getSession()->update($Image);
}

if ($mode == 'lastuploads') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/lastuploadsrss');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last additions')),array('title' => $Image->name_user));
}elseif ($mode == 'lasthits') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last viewed images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/lasthitsrss');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last viewed images')),array('title' => $Image->name_user));
}elseif ($mode == 'lastcommented') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/lastcommentedrss');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented images')),array('title' => $Image->name_user));
}elseif ($mode == 'lastrated') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last rated images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/lastratedrss');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last rated images')),array('title' => $Image->name_user));
}elseif ($mode == 'toprated') {	
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/topratedrss');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated images')),array('title' => $Image->name_user));
}elseif ($mode == 'popular') {	
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/popularrss');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular images')),array('title' => $Image->name_user));
}elseif ($mode == 'search') {
	
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search rss by keyword').' - '.htmlspecialchars($Params['user_parameters_unordered']['keyword']);
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/searchrss').'/(keyword)/'.urlencode($Params['user_parameters_unordered']['keyword']);
    
    $sortModesTitle = array (
        'new'               => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded first'),
        'newasc'            => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded last'),    
        'popular'           => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular first'),
        'popularasc'        => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular last'),    
        'lasthits'          => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last hits first'),
        'lasthitsasc'       => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last hits last'),    
        'lastcommented'     => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented first'),
        'lastcommentedasc'  => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented last'),    
        'toprated'          => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated first'),
        'topratedasc'       => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated last Last'),
        'lastrated'         => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last rated first'),
        'lastratedasc'      => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last rated last'),
        'relevance'         => '',
        'relevanceasc'      => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most relevance images last')
    );
    
    $Result['tittle_prepend'] = $sortModesTitle[$modeSort];
         
    $Result['keyword'] =urldecode($Params['user_parameters_unordered']['keyword']);  
    $Result['title_path'] = array(array('title' => urldecode($Params['user_parameters_unordered']['keyword']).' - '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','search results')),array('title' => $Image->name_user));
   
} else {
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded images to album').' - '.$Image->album_title;
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/albumrss').'/'.$Image->aid; 
}

$cache->store($cacheKeyImageView,$Result);

} elseif (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'delay_image_hit_enabled' ) == true) { // Delay image hit enabled
	erLhcoreClassModelGalleryDelayImageHit::addHit((int)$Params['user_parameters']['image_id']);
} elseif (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'delay_image_hit_log' ) == false) { // No access log hit enabled, so we must each time update picture stats
    
    if (!($Image instanceof erLhcoreClassModelGalleryImage)) {
        
        try {
            $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
        } catch (Exception $e){
            erLhcoreClassModule::redirect('/');
            exit;
        }
        $Image->hits++;
    	$Image->mtime = time();
    	erLhcoreClassGallery::getSession()->update($Image);
    }	
}

?>