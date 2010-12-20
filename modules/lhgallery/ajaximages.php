<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/ajaximages.tpl.php');
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );

$mode = isset($Params['user_parameters_unordered']['mode']) ? $Params['user_parameters_unordered']['mode'] : 'album';
$direction = (isset($Params['user_parameters_unordered']['direction']) && $Params['user_parameters_unordered']['direction'] == 'left') ? $Params['user_parameters_unordered']['direction'] : 'right';

$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );    
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';    
$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';

$filterArray = array();   
$appendMysqlIndex = array(); 
if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
    $appendMysqlIndex[] = 'res';
}    

$filterArray['approved'] = 1;
    
    
$tpl->set('direction',$direction);

if ($mode == 'album')
{
    // Index hint for mysql
    $useIndexHint = array(
        'new'               => 'pid_6',
        'newasc'            => 'pid_6',  
          
        'popular'           => 'pid_7',
        'popularasc'        => 'pid_7',  
          
        'lasthits'          => 'pid_8',
        'lasthitsasc'       => 'pid_8', 
           
        'lastcommented'     => 'pid_10',
        'lastcommentedasc'  => 'pid_10', 
           
        'toprated'          => 'pid_9',
        'topratedasc'       => 'pid_9',    
        
        'lastrated'         => 'a_rated_gen',
        'lastratedasc'      => 'a_rated_gen',
        
        //Hint if resolution filter is used
        'new_res'               => 'aid',
        'newasc_res'            => 'aid',
        
        'popular_res'           => 'pid_11',
        'popularasc_res'        => 'pid_11',
        
        'lasthits_res'          => 'aid_2',
        'lasthitsasc_res'       => 'aid_2',
        
        'lastcommented_res'     => 'aid_4',
        'lastcommentedasc_res'  => 'aid_4',
        
        'toprated_res'          => 'aid_3',
        'topratedasc_res'       => 'aid_3', 
        
        
        'lastrated_res'         => 'a_rated_gen_res',
        'lastratedasc_res'      => 'a_rated_gen_res',
        
    );
    
    $sortModes = array(    
        'new'              => 'pid DESC',
        'newasc'           => 'pid ASC',            
        'popular'          => 'hits DESC, pid DESC',
        'popularasc'       => 'hits ASC, pid ASC',        
        'lasthits'         => 'mtime DESC, pid DESC',
        'lasthitsasc'      => 'mtime ASC, pid ASC',        
        'lastcommented'    => 'comtime DESC, pid DESC',
        'lastcommentedasc' => 'comtime ASC, pid ASC',          
        'toprated'         => 'pic_rating DESC, votes DESC, pid DESC',
        'topratedasc'      => 'pic_rating ASC, votes ASC, pid ASC',    
        'lastrated'        => 'rtime DESC, pid DESC',
        'lastratedasc'     => 'rtime ASC, pid ASC'  
    );
    
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'new';
    $modeSQL = $sortModes[$modeSort];
        
    $modeIndex = $modeSort;
    if (count($appendMysqlIndex) > 0) {
        $modeIndex .= '_'.implode('_',$appendMysqlIndex);
    }
    
    if ($modeSort == 'new') {
        
        if ($direction == 'left') {
            $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true, 'use_index' => $useIndexHint[$modeIndex],'cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 6,'sort' => 'pid ASC','filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)));
            $imagesAjax = array_reverse($imagesAjax);
        } else $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'use_index' => $useIndexHint[$modeIndex],'cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 6,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)));        
           
    } elseif ($modeSort == 'newasc') {  
        
        if ($direction == 'left') {      
            $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'use_index' => $useIndexHint[$modeIndex],'cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 6,'sort' => 'pid DESC','filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)));
            $imagesAjax = array_reverse($imagesAjax);
        } else $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'use_index' => $useIndexHint[$modeIndex],'sort' => 'pid ASC','cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 6,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)));        
        
    } elseif ($modeSort == 'popular') {

        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_popular_'.$Image->pid.'_popular_version_'.$cache->getCacheVersion('most_popular_version',time(),1500).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {               
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                    
            if ($direction == 'left') {  
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'hits', $q2->bindValue( $Image->hits ) ). ' OR '.$q2->expr->eq( 'hits', $q2->bindValue( $Image->hits ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('hits ASC, pid ASC')
                ->limit( 6 );                
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );                
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                $imagesAjax = array_reverse($imagesAjax);   
            } else {            
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'hits', $q2->bindValue( $Image->hits ) ). ' OR '.$q2->expr->eq( 'hits', $q2->bindValue( $Image->hits ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('hits DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );             
            }
            
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
               
    } elseif ($modeSort == 'popularasc') {
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_popularasc_'.$Image->pid.'_popular_version_'.$cache->getCacheVersion('most_popular_version',time(),1500).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {        
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
           
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
                    
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );    
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') { 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'hits', $q2->bindValue( $Image->hits ) ). ' OR '.$q2->expr->eq( 'hits', $q2->bindValue( $Image->hits ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('hits DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                $imagesAjax = array_reverse($imagesAjax);
            } else {               
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'hits', $q2->bindValue( $Image->hits ) ). ' OR '.$q2->expr->eq( 'hits', $q2->bindValue( $Image->hits ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('hits ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );  
            }  
            
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }    
    } elseif ($modeSort == 'lasthits') {
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_lasthits_'.$Image->pid.'_lasthits_version_'.$cache->getCacheVersion('last_hits_version',time(),600).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {        
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                        
            if ($direction == 'left') {                   
                
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'mtime', $q2->bindValue( $Image->mtime ) ). ' OR '.$q2->expr->eq( 'mtime', $q2->bindValue( $Image->mtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('mtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                          
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                
                $imagesAjax = array_reverse($imagesAjax);
            } else {            
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'mtime', $q2->bindValue( $Image->mtime ) ). ' OR '.$q2->expr->eq( 'mtime', $q2->bindValue( $Image->mtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('mtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            }
            
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }

               
    } elseif ($modeSort == 'lasthitsasc') {
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_lasthitsasc_'.$Image->pid.'_lasthits_version_'.$cache->getCacheVersion('last_hits_version',time(),600).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {        
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                        
            if ($direction == 'left') {                   
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ( '.$q2->expr->lt( 'mtime', $q2->bindValue( $Image->mtime ) ). ' OR '.$q2->expr->eq( 'mtime', $q2->bindValue( $Image->mtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('mtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {    
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'mtime', $q2->bindValue( $Image->mtime ) ). ' OR '.$q2->expr->eq( 'mtime', $q2->bindValue( $Image->mtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('mtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            }
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
               
    } elseif ($modeSort == 'lastcommented') {
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_lastcommented_'.$Image->pid.'_lastcommented_version_'.$cache->getCacheVersion('last_commented_'.$Image->aid).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {        
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') { 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'comtime', $q2->bindValue( $Image->comtime ) ). ' OR '.$q2->expr->eq( 'comtime', $q2->bindValue( $Image->comtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('comtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else { 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'comtime', $q2->bindValue( $Image->comtime ) ). ' OR '.$q2->expr->eq( 'comtime', $q2->bindValue( $Image->comtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('comtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            }
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
                      
    } elseif ($modeSort == 'lastcommentedasc') {

        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_lastcommentedasc_'.$Image->pid.'_lastcommented_version_'.$cache->getCacheVersion('last_commented_'.$Image->aid).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {               
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {       
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'comtime', $q2->bindValue( $Image->comtime ) ). ' OR '.$q2->expr->eq( 'comtime', $q2->bindValue( $Image->comtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('comtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {                 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'comtime', $q2->bindValue( $Image->comtime ) ). ' OR '.$q2->expr->eq( 'comtime', $q2->bindValue( $Image->comtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('comtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            }
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
                       
    } elseif ($modeSort == 'lastrated') {
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_lastrated_'.$Image->pid.'_lastrated_version_'.$cache->getCacheVersion('last_rated_'.$Image->aid).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {        
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') { 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'rtime', $q2->bindValue( $Image->rtime ) ). ' OR '.$q2->expr->eq( 'rtime', $q2->bindValue( $Image->rtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('rtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else { 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'rtime', $q2->bindValue( $Image->rtime ) ). ' OR '.$q2->expr->eq( 'rtime', $q2->bindValue( $Image->rtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('rtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            }
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
                      
    } elseif ($modeSort == 'lastratedasc') {

        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_lastratedasc_'.$Image->pid.'_lastrated_version_'.$cache->getCacheVersion('last_rated_'.$Image->aid).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {               
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {       
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'rtime', $q2->bindValue( $Image->rtime ) ). ' OR '.$q2->expr->eq( 'rtime', $q2->bindValue( $Image->rtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('rtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {                 
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'rtime', $q2->bindValue( $Image->rtime ) ). ' OR '.$q2->expr->eq( 'rtime', $q2->bindValue( $Image->rtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ) .')')
                ->orderBy('rtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            }
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
                       
    } elseif ($modeSort == 'toprated') {
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_toprated_'.$Image->pid.'_toprated_version_'.$cache->getCacheVersion('top_rated_'.$Image->aid).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {               
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                     
            if ($direction == 'left') {    
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ). ' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->gt( 'votes', $q2->bindValue( $Image->votes ) ).' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->eq( 'votes', $q2->bindValue( $Image->votes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating ASC, votes ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax); 
            } else {              
                $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ). ' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->lt( 'votes', $q2->bindValue( $Image->votes ) ).' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->eq( 'votes', $q2->bindValue( $Image->votes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating DESC, votes DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            }     
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }
                        
    } elseif ($modeSort == 'topratedasc') {  
        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_topratedasc_'.$Image->pid.'_toprated_version_'.$cache->getCacheVersion('top_rated_'.$Image->aid).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {                         
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {  
            $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->lt( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ). ' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->lt( 'votes', $q2->bindValue( $Image->votes ) ).' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->eq( 'votes', $q2->bindValue( $Image->votes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating DESC, votes DESC, pid DESC')
            ->limit( 6 );
            $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);   
             } else {         
            $q2->where( $filterSQLString.$q2->expr->eq( 'aid', $q2->bindValue( $Image->aid ) ).' AND ('.$q2->expr->gt( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ). ' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->gt( 'votes', $q2->bindValue( $Image->votes ) ).' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->eq( 'votes', $q2->bindValue( $Image->votes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating ASC, votes ASC, pid ASC')
            ->limit( 6 );
            $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
            } 
            $cache->store($cacheKeyImage,$imagesAjax,0);
        }                        
    }    
        
    $hasMoreImages = 'false';
    
    
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
    
    $tpl->set('imagesAjax',$imagesAjax);
     
    $urlAppend = $modeSort != 'new' ? '/(sort)/'.$modeSort : '';
    $urlAppend .= $appendResolutionMode;
     
    $tpl->set('urlAppend',$urlAppend); 
     
} elseif ($mode == 'search') {
    $sortModes = array(    
        'new' => '@id DESC',
        'newasc' => '@id ASC',    
        'popular' => 'hits DESC, @id DESC',
        'popularasc' => 'hits ASC, @id ASC',          
        'lasthits'      => 'mtime DESC, @id DESC',
        'lasthitsasc'   => 'mtime ASC, @id ASC',        
        'lastcommented' => 'comtime DESC, @id DESC',
        'lastcommentedasc' => 'comtime ASC, @id ASC',          
        'toprated'         => 'pic_rating DESC, votes DESC, @id DESC',
        'topratedasc'      => 'pic_rating ASC, votes ASC, @id ASC', 
        'relevance'        => '@relevance DESC, @id DESC',
        'relevanceasc'     => '@relevance ASC, @id ASC'
    );
    
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'relevance';
    $modeSQL = $sortModes[$modeSort];
    $imagesAjax = array();

    // Because sphinx view already includes this filter
    unset($filterArray['approved']);
     
    $filterColor = array();
    // Color filter in search mode
    $appendColorMode = '';
    $pallete_id = (array)$Params['user_parameters_unordered']['color'];
    sort($pallete_id);
    $pallete_items_number = count($pallete_id);
    if ($pallete_items_number > 0) {  
        if ($pallete_items_number > erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters')) {
            $pallete_id = array_slice($pallete_id,0,erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));
            $pallete_items_number = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');
        }
        $filterColor = $pallete_id;
        $appendColorMode = '/(color)/'.implode('/',$pallete_id);
    }
    
      
    if ($modeSort == 'relevance') {
                    
        $relevanceCurrentImage = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'relevance' => true, 'SearchLimit' => 1,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC','Filter' => array('@id' => $Image->pid)));
           
        if ($direction == 'left') {
                
            $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filtergt' => array('pid' => $Image->pid),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filtergt' => array('@weight' => $relevanceCurrentImage),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
                            
                if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {
                    $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
                } elseif ($totalPhotosHigher['total_found'] > 0) {
                    $totalPhotos['list'] = $totalPhotosHigher['list'];
                }
                
                $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
           }            
    	   
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	   
    	} else {
        	
        	$totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filterlt' => array('pid' => $Image->pid-1),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filterlt' => array('@weight' => $relevanceCurrentImage-1),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
                                     
                if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {                           
                    $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
                } elseif ($totalPhotosHigher['total_found'] > 0) {
                    $totalPhotos['list'] = $totalPhotosHigher['list'];
                }                
                $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
            }
    	  
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif; 
    	}
    	
    } elseif ($modeSort == 'relevanceasc') {
                    
        $relevanceCurrentImage = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'relevance' => true, 'SearchLimit' => 1,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC','Filter' => array('@id' => $Image->pid)));
           
        if ($direction == 'left') {
                
            $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filterlt' => array('pid' => $Image->pid-1),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filterlt' => array('@weight' => $relevanceCurrentImage-1),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
                            
                if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {
                    $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
                } elseif ($totalPhotosHigher['total_found'] > 0) {
                    $totalPhotos['list'] = $totalPhotosHigher['list'];
                }
                
                $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
           }            
    	   
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	   
    	} else {
        	
        	$totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filtergt' => array('pid' => $Image->pid),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'filtergt' => array('@weight' => $relevanceCurrentImage),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
                                     
                if ($totalPhotosHigher['total_found'] > 0 && $totalPhotos['total_found'] > 0) {                           
                    $totalPhotos['list'] = (array)$totalPhotos['list'] + (array)$totalPhotosHigher['list'];
                } elseif ($totalPhotosHigher['total_found'] > 0) {
                    $totalPhotos['list'] = $totalPhotosHigher['list'];
                }                
                $totalPhotos['total_found'] += $totalPhotosHigher['total_found'];
            }
    	  
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif; 
    	}
    	
    } elseif ($modeSort == 'new') {
                       
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'SearchLimit' => 6,'keyword' =>  urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','Filter' => $filterArray,'filtergt' => array('pid' => $Image->pid)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'SearchLimit' => 6,'keyword' =>  urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','Filter' => $filterArray,'filterlt' => array('pid' => $Image->pid-1)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}  
                  
    } elseif ($modeSort == 'newasc') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','Filter' => $filterArray,'filterlt' => array('pid' => $Image->pid-1)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','Filter' => $filterArray,'filtergt' => array('pid' => $Image->pid)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}    	
             
    } elseif ($modeSort == 'popular') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	 
              
    } elseif ($modeSort == 'popularasc') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}  
         
    } elseif ($modeSort == 'lasthits') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	} 
        
    } elseif ($modeSort == 'lasthitsasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	              
    } elseif ($modeSort == 'lastcommented') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
             
    } elseif ($modeSort == 'lastcommentedasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	               
    } elseif ($modeSort == 'toprated') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	            
    } elseif ($modeSort == 'topratedasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('color_filter' => $filterColor,'custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}                     
    }
    
    
    $hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;

       
    $tpl->set('imagesAjax',$imagesAjax); 
    $urlAppend = $modeSort != 'relevance' ? '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'].'/(sort)/'.$modeSort : '/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword'];
    $urlAppend .= $appendColorMode.$appendResolutionMode;
     
    $tpl->set('urlAppend',$urlAppend);  
    
} elseif ($mode == 'lastuploads') {
      
	if ($direction == 'left'){
	   $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'filter' => $filterArray,'limit' => 6,'sort' => 'pid ASC','filtergt' => array('pid' => $Image->pid)));
	   rsort($imagesAjax);   
	} else {
	   $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'filter' => $filterArray,'limit' => 6,'sort' => 'pid DESC','filterlt' => array('pid' => $Image->pid))); 
	} 
	
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax);
    
    $urlAppend = '/(mode)/lastuploads';
    $urlAppend .= $appendResolutionMode;
    
	$tpl->set('urlAppend',$urlAppend);
	
} elseif ($mode == 'lasthits') {
    
    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'lasthits_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('last_hits_version',time(),600).'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
        
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {          	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
    	
        $q2 = $q->subSelect();
        $q2->select( 'pid' )->from( 'lh_gallery_images' );
            
    	 $filterSQLArray = array(); 
         $filterSQLString = '';
         
         $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );        
         if ($resolution != '') {
            $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );
         }
         
    	 $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
         
    	if ($direction == 'left'){
    	   $q2->where( $filterSQLString.'('.$q2->expr->gt( 'mtime', $q2->bindValue( $Image->mtime ) ). ' OR '.$q2->expr->eq( 'mtime', $q2->bindValue( $Image->mtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 6 );
            $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	   $q2->where( $filterSQLString.'('.$q2->expr->lt( 'mtime', $q2->bindValue( $Image->mtime ) ). ' OR '.$q2->expr->eq( 'mtime', $q2->bindValue( $Image->mtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 6 );
            $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	}
    	
    	$cache->store($cacheKeyImage,$imagesAjax,0);
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax); 
    
    $urlAppend = '/(mode)/lasthits';
    $urlAppend .= $appendResolutionMode;  
      
	$tpl->set('urlAppend',$urlAppend);
	
} elseif ($mode == 'popular') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'popular_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('most_popular_version',time(),1500).'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {      	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
    	  
    	$q2 = $q->subSelect();
        $q2->select( 'pid' )->from( 'lh_gallery_images' );
        
    	 $filterSQLArray = array(); 
         $filterSQLString = '';  
         $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );   
         if ($resolution != '') {
            $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );
         }
              
    	 $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
              
    	if ($direction == 'left'){
    	    $q2->where( $filterSQLString.'('.$q2->expr->gt( 'hits', $q2->bindValue( $Image->hits ) ). ' OR '.$q2->expr->eq( 'hits', $q2->bindValue( $Image->hits ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 6 );
            $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	    $q2->where( $filterSQLString.'('.$q2->expr->lt( 'hits', $q2->bindValue( $Image->hits ) ). ' OR '.$q2->expr->eq( 'hits', $q2->bindValue( $Image->hits ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits DESC, pid DESC')
            ->limit( 6 );
            $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	};	
    	$cache->store($cacheKeyImage,$imagesAjax,0);
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax); 
    
	$urlAppend = '/(mode)/popular';
    $urlAppend .= $appendResolutionMode;  
      
	$tpl->set('urlAppend',$urlAppend);
	
	
} elseif ($mode == 'popularrecent') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'popularrecent_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('popularrecent_version',time(),600).'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryPopular24' );   
    	
    	$hitsRecent = erLhcoreClassModelGalleryPopular24::fetch($Image->pid); 
            
    	if ($direction == 'left'){
    	    $q->where( '('.$q->expr->gt( 'hits', $q->bindValue( $hitsRecent->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $hitsRecent->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 6 );
            $imagesAjaxRecent = $session->find( $q, 'erLhcoreClassModelGalleryPopular24' );
            $imagesAjaxRecent = array_reverse($imagesAjaxRecent);
    	} else {
    	    $q->where( '('.$q->expr->lt( 'hits', $q->bindValue( $hitsRecent->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $hitsRecent->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits DESC, pid DESC')
            ->limit( 6 );
            $imagesAjaxRecent = $session->find( $q, 'erLhcoreClassModelGalleryPopular24' );
    	};	
    		
    	foreach ($imagesAjaxRecent as $imageRecent)
        {
        	$imagesAjax[] = $imageRecent->image;
        }
        
        $cache->store($cacheKeyImage,$imagesAjax,0);
    }
     
     	
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax); 
    
	$urlAppend = '/(mode)/popularrecent';
    $urlAppend .= $appendResolutionMode;  
      
	$tpl->set('urlAppend',$urlAppend);
	
	
} elseif ($mode == 'ratedrecent') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'ratedrecent_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('ratedrecent_version').'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryRated24' );   
    	
    	$ratedRecent = erLhcoreClassModelGalleryRated24::fetch($Image->pid); 
            
    	if ($direction == 'left'){
    	    $q->where( '('.$q->expr->gt( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $ratedRecent->votes ) ).' OR '.
                $q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $ratedRecent->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $ratedRecent->pid ) ).')')
                ->orderBy('pic_rating ASC, votes ASC, pid ASC')
                ->limit( 6 );
            $imagesAjaxRecent = $session->find( $q, 'erLhcoreClassModelGalleryRated24' );
            $imagesAjaxRecent = array_reverse($imagesAjaxRecent);
    	} else {
    	    $q->where( '('.$q->expr->lt( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $ratedRecent->votes ) ).' OR '.
                $q->expr->eq( 'pic_rating', $q->bindValue( $ratedRecent->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $ratedRecent->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $ratedRecent->pid ) ).')')
                ->orderBy('pic_rating DESC, votes DESC, pid DESC')
                ->limit( 6 );
            $imagesAjaxRecent = $session->find( $q, 'erLhcoreClassModelGalleryRated24' );
    	};	
    		
    	foreach ($imagesAjaxRecent as $imageRecent)
        {
        	$imagesAjax[] = $imageRecent->image;
        }
        
        $cache->store($cacheKeyImage,$imagesAjax,0);
    }
          	
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax); 
    
	$urlAppend = '/(mode)/ratedrecent';
    $urlAppend .= $appendResolutionMode;  
      
	$tpl->set('urlAppend',$urlAppend);
	
	
} elseif ($mode == 'lastcommented') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'lastcommented_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('last_commented').'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {      	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
    	
    	$q2 = $q->subSelect();
        $q2->select( 'pid' )->from( 'lh_gallery_images' );
        
    	$filterSQLArray = array(); 
        $filterSQLString = ''; 
        $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );   
        if ($resolution != '') {
            $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );        
        }
        $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
          	
    	if ($direction == 'left'){
    	    $q2->where(  $filterSQLString.'('.$q2->expr->gt( 'comtime', $q2->bindValue( $Image->comtime ) ). ' OR '.$q2->expr->eq( 'comtime', $q2->bindValue( $Image->comtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('comtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	     $q2->where(  $filterSQLString.'('.$q2->expr->lt( 'comtime', $q2->bindValue( $Image->comtime ) ). ' OR '.$q2->expr->eq( 'comtime', $q2->bindValue( $Image->comtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('comtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	};	
    	
    	$cache->store($cacheKeyImage,$imagesAjax,0);
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax);
     
	$urlAppend = '/(mode)/lastcommented';
    $urlAppend .= $appendResolutionMode;        
	$tpl->set('urlAppend',$urlAppend);
	
} elseif ($mode == 'color') {

    
    sort((array)$Params['user_parameters_unordered']['color']);
    
    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'color_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('color_images').'_direction_'.$direction.'_filter_color_'.erLhcoreClassGallery::multi_implode(',',$Params['user_parameters_unordered']['color']);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {      
        // Protection against to mutch color filters
        if (count($Params['user_parameters_unordered']['color']) > erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters')) {
            $Params['user_parameters_unordered']['color'] = array_slice($Params['user_parameters_unordered']['color'],0,erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));    
        }
        	
        $imagesAjax = erLhcoreClassModelGalleryPallete::getAjaxImages($Image->pid,(array)$Params['user_parameters_unordered']['color'],$direction);
    	    	
    	$cache->store($cacheKeyImage,$imagesAjax,0);
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax);
     
	$urlAppend = '/(mode)/color/(color)/'.implode('/',$Params['user_parameters_unordered']['color']);
          
	$tpl->set('urlAppend',$urlAppend);
	
} elseif ($mode == 'lastrated') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'lastrated_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('last_rated').'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {      	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
    	
    	$q2 = $q->subSelect();
        $q2->select( 'pid' )->from( 'lh_gallery_images' );
        
    	$filterSQLArray = array(); 
        $filterSQLString = ''; 
        $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );   
        if ($resolution != '') {
            $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );        
        }
        $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
          	
    	if ($direction == 'left'){
    	    $q2->where(  $filterSQLString.'('.$q2->expr->gt( 'rtime', $q2->bindValue( $Image->rtime ) ). ' OR '.$q2->expr->eq( 'rtime', $q2->bindValue( $Image->rtime ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('rtime ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	    $q2->where(  $filterSQLString.'('.$q2->expr->lt( 'rtime', $q2->bindValue( $Image->rtime ) ). ' OR '.$q2->expr->eq( 'rtime', $q2->bindValue( $Image->rtime ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')' )
                ->orderBy('rtime DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	};	
    	
    	$cache->store($cacheKeyImage,$imagesAjax,0);
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax);
     
	$urlAppend = '/(mode)/lastrated';
    $urlAppend .= $appendResolutionMode;        
	$tpl->set('urlAppend',$urlAppend);
	
} elseif ($mode == 'toprated') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'toprated_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('top_rated').'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {      	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
    	
    	$q2 = $q->subSelect();
        $q2->select( 'pid' )->from( 'lh_gallery_images' );
        
    	$filterSQLArray = array(); 
        $filterSQLString = ''; 
        $filterSQLArray[] = $q2->expr->eq( 'approved', $q2->bindValue( 1 ) );   
        if ($resolution != '') {
            $filterSQLArray[] = $q2->expr->eq( 'pwidth', $q2->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q2->expr->eq( 'pheight', $q2->bindValue( $resolutions[$resolution]['height'] ) );        
        }
        $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
              	
    	if ($direction == 'left'){
    	     $q2->where( $filterSQLString.'('.$q2->expr->gt( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ). ' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->gt( 'votes', $q2->bindValue( $Image->votes ) ).' OR '.
                $q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->eq( 'votes', $q2->bindValue( $Image->votes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating ASC, votes ASC, pid ASC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	       $q2->where( $filterSQLString.'('.$q2->expr->lt( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ). ' OR '.$q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->lt( 'votes', $q2->bindValue( $Image->votes ) ).' OR '.
                $q2->expr->eq( 'pic_rating', $q2->bindValue( $Image->pic_rating ) ).' AND '.$q2->expr->eq( 'votes', $q2->bindValue( $Image->votes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating DESC, votes DESC, pid DESC')
                ->limit( 6 );
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	};
    	$cache->store($cacheKeyImage,$imagesAjax,0);
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax); 
    
    $urlAppend = '/(mode)/toprated';
    $urlAppend .= $appendResolutionMode;
	$tpl->set('urlAppend',$urlAppend);
	
} elseif ($mode == 'myfavorites') {
           	    
    $favouriteSession = erLhcoreClassModelGalleryMyfavoritesSession::getInstance();	
         	
	if ($direction == 'left'){
	       $imagesAjaxFav = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('cache_key' => 'favorite_image_'.CSCacheAPC::getMem()->getCacheVersion('favorite_'.$favouriteSession->id),'limit' => 6,'sort' => 'pid ASC','filter' => array('session_id' => $favouriteSession->id),'filtergt' => array('pid' => $Image->pid)));
           $imagesAjaxFav = array_reverse($imagesAjaxFav);
	} else {
	       $imagesAjaxFav = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('cache_key' => 'favorite_image_'.CSCacheAPC::getMem()->getCacheVersion('favorite_'.$favouriteSession->id),'limit' => 6,'filter' => array('session_id' => $favouriteSession->id),'filterlt' => array('pid' => $Image->pid)));        
	};	

	foreach ($imagesAjaxFav as $imageFav)
    {
        $imageItem = $imageFav->image;
                                
        if ($imageItem !== false) {
    	   $imagesAjax[] = $imageItem;
        }
    }
    
	$hasMoreImages = 'false';
        
    if (count($imagesAjax) > 5) {
        $hasMoreImages = 'true';
        if ($direction == 'left') { 
            $imagesAjax = array_slice($imagesAjax,1,5);
        } else {
            $imagesAjax = array_slice($imagesAjax,0,5);
        }    
    }

    $imagesFound = count($imagesAjax);
    
    reset($imagesAjax);
    $ImageLast = current($imagesAjax);    
    $LeftImagePID = $ImageLast->pid;
    
    end($imagesAjax);
    $ImageLast = current($imagesAjax);
    $RightImagePID = $ImageLast->pid;
           
    $tpl->set('imagesAjax',$imagesAjax); 
	$tpl->set('urlAppend','/(mode)/myfavorites');
}


$tpl->set('mode',$mode);
$tpl->set('keyword',isset($Params['user_parameters_unordered']['keyword']) ? urldecode($Params['user_parameters_unordered']['keyword']) : '');
$tpl->set('image',$Image);


$content = trim($tpl->fetch());

echo json_encode(array('result' => $content, 'error' => $content == '' ? 'true' : 'false','has_more_images' => $hasMoreImages,'left_img_pid' => $LeftImagePID,'right_img_pid' => $RightImagePID, 'images_found' => $imagesFound));
exit;
?>