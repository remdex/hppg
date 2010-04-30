<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/ajaximages.tpl.php');
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );

$mode = isset($Params['user_parameters_unordered']['mode']) ? $Params['user_parameters_unordered']['mode'] : 'album';
$direction = (isset($Params['user_parameters_unordered']['direction']) && $Params['user_parameters_unordered']['direction'] == 'left') ? $Params['user_parameters_unordered']['direction'] : 'right';
$tpl->set('direction',$direction);

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
        
        if ($direction == 'left') {
            $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 5,'sort' => 'pid ASC','filter' => array('aid' => $Image->aid),'filtergt' => array('pid' => $Image->pid)));
            $imagesAjax = array_reverse($imagesAjax);
        } else $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 5,'filter' => array('aid' => $Image->aid),'filterlt' => array('pid' => $Image->pid)));        
           
    } elseif ($modeSort == 'newasc') {  
        
        if ($direction == 'left') {      
            $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 5,'sort' => 'pid DESC','filter' => array('aid' => $Image->aid),'filterlt' => array('pid' => $Image->pid)));
            $imagesAjax = array_reverse($imagesAjax);
        } else $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('sort' => 'pid ASC','cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 5,'filter' => array('aid' => $Image->aid),'filtergt' => array('pid' => $Image->pid)));        
        
    } elseif ($modeSort == 'popular') {
                
        $session = erLhcoreClassGallery::getSession();
        if ($direction == 'left') {      
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            $imagesAjax = array_reverse($imagesAjax);   
        } else {
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );             
        }
               
    } elseif ($modeSort == 'popularasc') {
        
        $session = erLhcoreClassGallery::getSession();        
        if ($direction == 'left') {     
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('hits DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            $imagesAjax = array_reverse($imagesAjax);
        } else {               
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );  
        }       
    } elseif ($modeSort == 'lasthits') {
        
        $session = erLhcoreClassGallery::getSession();
        if ($direction == 'left') {                    
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
        } else {            
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        }
                
    } elseif ($modeSort == 'lasthitsasc') {
        
        $session = erLhcoreClassGallery::getSession(); 
        if ($direction == 'left') {                   
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ( '.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
        } else {               
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
        }
                       
    } elseif ($modeSort == 'lastcommented') {
        $session = erLhcoreClassGallery::getSession();         
        if ($direction == 'left') {      
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('comtime ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
        } else { 
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('comtime DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
        }
                      
    } elseif ($modeSort == 'lastcommentedasc') {
                
        $session = erLhcoreClassGallery::getSession();
        if ($direction == 'left') {       
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('comtime DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
        } else {                 
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
            ->orderBy('comtime ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        }
        
                       
    } elseif ($modeSort == 'toprated') {
               
        $session = erLhcoreClassGallery::getSession(); 
        if ($direction == 'left') {    
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating ASC, votes ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax); 
        } else {              
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating DESC, votes DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
        }     
                        
    } elseif ($modeSort == 'topratedasc') {               
        $session = erLhcoreClassGallery::getSession();
        if ($direction == 'left') {       
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
        ->orderBy('pic_rating DESC, votes DESC, pid DESC')
        ->limit( 5 );
        $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        $imagesAjax = array_reverse($imagesAjax);   
         } else { 
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
        ->orderBy('pic_rating ASC, votes ASC, pid ASC')
        ->limit( 5 );
        $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
        }                         
    }    
    
    $tpl->set('imagesAjax',$imagesAjax);  
    $tpl->set('urlAppend',$modeSort != 'newdesc' ? '/(sort)/'.$modeSort : ''); 
     
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
    $imagesAjax = array();
    
    if ($modeSort == 'newdesc') {
                       
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' =>  urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','filtergt' => array('pid' => $Image->pid)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' =>  urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','filterlt' => array('pid' => $Image->pid-1)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}  
                  
    } elseif ($modeSort == 'newasc') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','filterlt' => array('pid' => $Image->pid-1)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','filtergt' => array('pid' => $Image->pid)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}    	
             
    } elseif ($modeSort == 'popular') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	 
              
    } elseif ($modeSort == 'popularasc') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}  
         
    } elseif ($modeSort == 'lasthits') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	} 
        
    } elseif ($modeSort == 'lasthitsasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	              
    } elseif ($modeSort == 'lastcommented') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 5,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
             
    } elseif ($modeSort == 'lastcommentedasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'SearchLimit' => 2,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'SearchLimit' => 2,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	               
    } elseif ($modeSort == 'toprated') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 2,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 2,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	            
    } elseif ($modeSort == 'topratedasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 2,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'SearchLimit' => 2,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}                     
    }
        
    $tpl->set('imagesAjax',$imagesAjax);  
    $tpl->set('urlAppend',$modeSort != 'newdesc' ? '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'].'/(sort)/'.$modeSort : '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword']);  
    
}


$tpl->set('mode',$mode);
$tpl->set('keyword',isset($Params['user_parameters_unordered']['keyword']) ? urldecode($Params['user_parameters_unordered']['keyword']) : '');
$tpl->set('image',$Image);


$content = trim($tpl->fetch());

echo json_encode(array('result' => $content, 'error' => $content == '' ? 'true' : 'false'));
exit;
?>