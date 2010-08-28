<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/image.tpl.php');
try{
    $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$CommentData = new erLhcoreClassModelGalleryComment();
$currentUser = erLhcoreClassUser::instance();

if ($currentUser->isLogged()){
    $CommentData->msg_author = $currentUser->getUserData()->username;
} else {
    $CommentData->msg_author = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Guest_');
}

if (isset($_POST['StoreComment']))
{      
    $definition = array(
        'Name' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),
        
        'CommentBody' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),   
            
        'CaptchaCode' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'CaptchaCode' ) || $form->CaptchaCode == '' || $form->CaptchaCode != $_SESSION[$_SERVER['REMOTE_ADDR']]['comment'] )
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
        
        //Clear cache
        CSCacheAPC::getMem()->delete('comments_'.$Image->pid);
        CSCacheAPC::getMem()->increaseCacheVersion('last_commented');
        
        
        $tpl->set('commentStored',true);
             
    }  else {
         
        $tpl->set('commentErrArr',$Errors);
    }
    
}


    
// Display mode - album, lastupload
$mode = isset($Params['user_parameters_unordered']['mode']) ? $Params['user_parameters_unordered']['mode'] : 'album';

if ($mode == 'album')
{
    
    $sortModes = array(    
        'newdesc'       => 'pid DESC',
        'newasc'        => 'pid ASC',            
        'popular'       => 'hits DESC, pid DESC',
        'popularasc'    => 'hits ASC, pid ASC',        
        'lasthits'      => 'mtime DESC, pid DESC',
        'lasthitsasc'   => 'mtime ASC, pid ASC',        
        'lastcommented' => 'comtime DESC, pid DESC',
        'lastcommentedasc' => 'comtime ASC, pid ASC',          
        'toprated'         => 'pic_rating DESC, votes DESC, pid DESC',
        'topratedasc'      => 'pic_rating ASC, votes ASC, pid ASC',  
    );
    
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'newdesc';
    $modeSQL = $sortModes[$modeSort];
    
    if ($modeSort == 'newdesc') {                
        $imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 5,'sort' => 'pid ASC','filter' => array('aid' => $Image->aid),'filtergt' => array('pid' => $Image->pid)));
        $page = ceil((erLhcoreClassModelGalleryImage::getImageCount(array('filter' => array('aid' => $Image->aid),'filtergt' => array('pid' => $Image->pid)))+1)/20);
        $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 5,'filter' => array('aid' => $Image->aid),'filterlt' => array('pid' => $Image->pid)));        
    } elseif ($modeSort == 'newasc') {        
        $imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 5,'sort' => 'pid DESC','filter' => array('aid' => $Image->aid),'filterlt' => array('pid' => $Image->pid)));
        $page = ceil((erLhcoreClassModelGalleryImage::getImageCount(array('filter' => array('aid' => $Image->aid),'filterlt' => array('pid' => $Image->pid)))+1)/20);
        $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('sort' => 'pid ASC','cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 5,'filter' => array('aid' => $Image->aid),'filtergt' => array('pid' => $Image->pid)));        
    } elseif ($modeSort == 'popular') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
        ->orderBy('hits ASC, pid ASC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (hits > :hits OR hits = :hits AND pid > :pid) AND aid = :aid LIMIT 1');
        $stmt->bindValue( ':hits',$Image->hits);
        $stmt->bindValue( ':pid',$Image->pid);       
        $stmt->bindValue( ':aid',$Image->aid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();                 
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
        ->orderBy('hits DESC, pid DESC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );         
    } elseif ($modeSort == 'popularasc') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
        ->orderBy('hits DESC, pid DESC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (hits < :hits OR hits = :hits AND pid < :pid) AND aid = :aid LIMIT 1');
        $stmt->bindValue( ':hits',$Image->hits);
        $stmt->bindValue( ':pid',$Image->pid);       
        $stmt->bindValue( ':aid',$Image->aid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();                 
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
        ->orderBy('hits ASC, pid ASC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );         
    } elseif ($modeSort == 'lasthits') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
        ->orderBy('mtime ASC, pid ASC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (mtime > :mtime OR mtime = :mtime AND pid > :pid) AND aid = :aid LIMIT 1');
        $stmt->bindValue( ':mtime',$Image->mtime);
        $stmt->bindValue( ':pid',$Image->pid);   
        $stmt->bindValue( ':aid',$Image->aid);    
        $stmt->execute();  
        $photos = $stmt->fetchColumn();         
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
        ->orderBy('mtime DESC, pid DESC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                
    } elseif ($modeSort == 'lasthitsasc') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ( '.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
        ->orderBy('mtime DESC, pid DESC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (mtime < :mtime OR mtime = :mtime AND pid < :pid) AND aid = :aid LIMIT 1');
        $stmt->bindValue( ':mtime',$Image->mtime);
        $stmt->bindValue( ':pid',$Image->pid);   
        $stmt->bindValue( ':aid',$Image->aid);    
        $stmt->execute();  
        $photos = $stmt->fetchColumn();         
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
        ->orderBy('mtime ASC, pid ASC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                       
    } elseif ($modeSort == 'lastcommented') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
        ->orderBy('comtime ASC, pid ASC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (comtime > :comtime OR comtime = :comtime AND pid > :pid) AND aid = :aid LIMIT 1');
        $stmt->bindValue( ':comtime',$Image->comtime);
        $stmt->bindValue( ':pid',$Image->pid);   
        $stmt->bindValue( ':aid',$Image->aid);    
        $stmt->execute();
        $photos = $stmt->fetchColumn();
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
        ->orderBy('comtime DESC, pid DESC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                      
    } elseif ($modeSort == 'lastcommentedasc') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
        ->orderBy('comtime DESC, pid DESC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (comtime < :comtime OR comtime = :comtime AND pid < :pid) AND aid = :aid LIMIT 1');
        $stmt->bindValue( ':comtime',$Image->comtime);
        $stmt->bindValue( ':pid',$Image->pid);   
        $stmt->bindValue( ':aid',$Image->aid);    
        $stmt->execute();
        $photos = $stmt->fetchColumn();
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
        ->orderBy('comtime ASC, pid ASC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                       
    } elseif ($modeSort == 'toprated') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
        ->orderBy('pic_rating ASC, votes ASC, pid ASC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );  
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid > :pid) AND aid = :aid');
        $stmt->bindValue( ':pic_rating',$Image->pic_rating);       
        $stmt->bindValue( ':votes',$Image->votes);       
        $stmt->bindValue( ':pid',$Image->pid);
        $stmt->bindValue( ':aid',$Image->aid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();         
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
        ->orderBy('pic_rating DESC, votes DESC, pid DESC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );                      
    } elseif ($modeSort == 'topratedasc') {
        
        $db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
        ->orderBy('pic_rating DESC, votes DESC, pid DESC')
        ->limit( 5 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );  
        
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE (pic_rating < :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes < :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid < :pid) AND aid = :aid');
        $stmt->bindValue( ':pic_rating',$Image->pic_rating);       
        $stmt->bindValue( ':votes',$Image->votes);       
        $stmt->bindValue( ':pid',$Image->pid);
        $stmt->bindValue( ':aid',$Image->aid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();         
        $page = ceil(($photos+1)/20);
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
        ->orderBy('pic_rating ASC, votes ASC, pid ASC')
        ->limit( 5 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );                     
    }    
    
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';
    $urlAppend = $modeSort != 'newdesc' ? '/(sort)/'.$modeSort : '';             
    
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',$Image->album->url_path.$pageAppend.$urlAppend);   
    $tpl->setArray($imagesParams);           
    
} elseif ($mode == 'search') {
    
    $sortModes = array(    
        'newdesc' => '@id DESC',
        'newasc' => '@id ASC',    
        'popular' => 'hits DESC, @id DESC',
        'popularasc' => 'hits ASC, @id ASC',          
        'lasthits'      => 'mtime DESC, @id DESC',
        'lasthitsasc'   => 'mtime ASC, @id ASC',        
        'lastcommented' => 'comtime DESC, @id DESC',
        'lastcommentedasc' => 'comtime ASC, @id ASC',          
        'toprated'         => 'pic_rating DESC, votes DESC, @id DESC',
        'topratedasc'      => 'pic_rating ASC, votes ASC, @id ASC', 
    );
    
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'newdesc';
    $modeSQL = $sortModes[$modeSort];
    
    if ($modeSort == 'newdesc') {        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','filtergt' => array('pid' => $Image->pid)));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','filterlt' => array('pid' => $Image->pid-1)));
        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
                  
    } elseif ($modeSort == 'newasc') {
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','filterlt' => array('pid' => $Image->pid-1)));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','filtergt' => array('pid' => $Image->pid)));
        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array(); 
    } elseif ($modeSort == 'popular') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();   
    } elseif ($modeSort == 'popularasc') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();   
    } elseif ($modeSort == 'lasthits') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
    } elseif ($modeSort == 'lasthitsasc') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();               
    } elseif ($modeSort == 'lastcommented') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();  
    } elseif ($modeSort == 'lastcommentedasc') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));        
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();               
    } elseif ($modeSort == 'toprated') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();             
    } elseif ($modeSort == 'topratedasc') {
        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
        if ($totalPhotos['total_found'] > 0)
            $imagesLeft = $totalPhotos['list']; 
        else
            $imagesLeft = array();
                              
        $page = ceil(($totalPhotos['total_found']+1)/20);	
                        
        $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
        if ($totalPhotos['total_found'] > 0)               
            $imagesRight = $totalPhotos['list']; 
        else 
            $imagesRight = array();             
    }
       
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = $modeSort != 'newdesc' ? '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'].'/(sort)/'.$modeSort : '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'];             
    
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/search').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams); 
    
} elseif ($mode == 'myfavorites') {

	$favouriteSession = erLhcoreClassModelGalleryMyfavoritesSession::getInstance();	
	$imagesLeftArray = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('cache_key' => 'favorite_image_'.CSCacheAPC::getMem()->getCacheVersion('favorite_'.$favouriteSession->id),'limit' => 5,'sort' => 'pid ASC','filter' => array('session_id' => $favouriteSession->id),'filtergt' => array('pid' => $Image->pid)));
    $page = ceil((erLhcoreClassModelGalleryMyfavoritesImage::getImageCount(array('filter' => array('session_id' => $favouriteSession->id),'filtergt' => array('pid' => $Image->pid)))+1)/20);
    $imagesRightArray = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('cache_key' => 'favorite_image_'.CSCacheAPC::getMem()->getCacheVersion('favorite_'.$favouriteSession->id),'limit' => 5,'filter' => array('session_id' => $favouriteSession->id),'filterlt' => array('pid' => $Image->pid)));        
    $imagesLeft = array();
    $imagesRight = array();
    
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
    $urlAppend = '/(mode)/myfavorites';             
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/myfavorites').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);	
	
} elseif ($mode == 'lastuploads') {
               
    $imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 5,'sort' => 'pid ASC','filtergt' => array('pid' => $Image->pid)));    	
    $page = ceil((erLhcoreClassModelGalleryImage::getImageCount(array('filtergt' => array('pid' => $Image->pid)))+1)/20);
    $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 5,'sort' => 'pid DESC','filterlt' => array('pid' => $Image->pid)));
     	
	$imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lastuploads';             
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/lastuploads').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);	
	
	
} elseif ($mode == 'lasthits') {

    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) )
    ->orderBy('mtime ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) )
    ->orderBy('mtime DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE mtime > :mtime OR mtime = :mtime AND pid > :pid LIMIT 1');
    $stmt->bindValue( ':mtime',$Image->mtime);
    $stmt->bindValue( ':pid',$Image->pid);       
    $stmt->execute();  
    $photos = $stmt->fetchColumn();         
    $page = ceil(($photos+1)/20);
    
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lasthits';             
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/lasthits').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);
	
} elseif ($mode == 'popular'){
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) )
    ->orderBy('hits ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) )
    ->orderBy('hits DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE hits > :hits OR hits = :hits AND pid > :pid LIMIT 1');
    $stmt->bindValue( ':hits',$Image->hits);
    $stmt->bindValue( ':pid',$Image->pid);       
    $stmt->execute();
    $photos = $stmt->fetchColumn(); 
	           
    $page = ceil(($photos+1)/20);
            
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/popular';             
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/popular').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);
       	
}   elseif ($mode == 'lastcommented'){
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) )
    ->orderBy('comtime ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) )
    ->orderBy('comtime DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
    $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE comtime > :comtime OR comtime = :comtime AND pid > :pid LIMIT 1');
    $stmt->bindValue( ':comtime',$Image->comtime);
    $stmt->bindValue( ':pid',$Image->pid);       
    $stmt->execute();
    $photos = $stmt->fetchColumn();
    $page = ceil(($photos+1)/20);
       
    $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
    $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
    $urlAppend = '/(mode)/lastcommented';             
        
    $tpl->set('urlAppend',$urlAppend);       
    $tpl->set('urlReturnToThumbnails',erLhcoreClassDesign::baseurl('gallery/lastcommented').$urlAppend.$pageAppend);   
    $tpl->setArray($imagesParams);
     	
} elseif ($mode == 'toprated'){
    $db = ezcDbInstance::get(); 
    $session = erLhcoreClassGallery::getSession(); 
        
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.
    $q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ))
    ->orderBy('pic_rating ASC, votes ASC, pid ASC')
    ->limit( 5 );
    $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
          
    $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    $q->where( $q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.
    $q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ))
    ->orderBy('pic_rating DESC, votes DESC, pid DESC')
    ->limit( 5 );
    $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            
   $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid > :pid');
   $stmt->bindValue( ':pic_rating',$Image->pic_rating);       
   $stmt->bindValue( ':votes',$Image->votes);       
   $stmt->bindValue( ':pid',$Image->pid);       
   $stmt->execute();
   $photos = $stmt->fetchColumn();         
   $page = ceil(($photos+1)/20);
           
   $imagesParams = erLhcoreClassModelGalleryImage::getImagesSlices($imagesLeft, $imagesRight, $Image);
   $pageAppend = $page > 1 ? '/(page)/'.$page : '';    
   $urlAppend = '/(mode)/toprated';             
        
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

// Must be in the bottom
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'delay_image_hit_enabled' ) == true){
	erLhcoreClassModelGalleryDelayImageHit::addHit($Image->pid);
} else {
	$Image->hits++;
	$Image->mtime = time();
	erLhcoreClassGallery::getSession()->update($Image);
}

if ($mode == 'lastuploads') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/lastuploadsrss/');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last additions')),array('title' => $Image->name_user));
}elseif ($mode == 'lasthits') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last viewed images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/lasthitsrss/');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last viewed images')),array('title' => $Image->name_user));
}elseif ($mode == 'lastcommented') {
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/lastcommentedrss/');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented images')),array('title' => $Image->name_user));
}elseif ($mode == 'toprated') {	
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/topratedrss/');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated images')),array('title' => $Image->name_user));
}elseif ($mode == 'popular') {	
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/popularrss/');
    $Result['title_path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular images')),array('title' => $Image->name_user));
}elseif ($mode == 'search') {
	
	$Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search rss by keyword').' - '.htmlspecialchars($Params['user_parameters_unordered']['keyword']);
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/searchrss').'/(keyword)/'.urlencode($Params['user_parameters_unordered']['keyword']);
    
    $sortModesTitle = array(    
        'newdesc' => '',
        'newasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded last'),    
        'popular' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular first'),
        'popularasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Most popular last'),    
        'lasthits' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last hits first'),
        'lasthitsasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last hits last'),    
        'lastcommented' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented first'),
        'lastcommentedasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last commented last'),    
        'toprated' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated first'),
        'topratedasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Top rated last Last'));        
    $Result['tittle_prepend'] = $sortModesTitle[$modeSort];
         
    $Result['keyword'] =urldecode($Params['user_parameters_unordered']['keyword']);  
    $Result['title_path'] = array(array('title' => urldecode($Params['user_parameters_unordered']['keyword']).' - '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','search results')),array('title' => $Image->name_user));
   
} else {
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Last uploaded images to album').' - '.$Image->album->title;
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/albumrss/').$Image->aid; 
}



?>