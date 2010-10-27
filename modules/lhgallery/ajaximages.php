<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/ajaximages.tpl.php');
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );

$mode = isset($Params['user_parameters_unordered']['mode']) ? $Params['user_parameters_unordered']['mode'] : 'album';
$direction = (isset($Params['user_parameters_unordered']['direction']) && $Params['user_parameters_unordered']['direction'] == 'left') ? $Params['user_parameters_unordered']['direction'] : 'right';

$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );    
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';    
$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';

$filterArray = array();    
if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
}    

$filterArray['approved'] = 1;
    
$tpl->set('direction',$direction);

if ($mode == 'album')
{
    
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
        'lastrated' => 'rtime DESC, pid DESC',
        'lastratedasc' => 'rtime ASC, pid ASC'  
    );
    
    $modeSort = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'new';
    $modeSQL = $sortModes[$modeSort];
    
    if ($modeSort == 'new') {
        
        if ($direction == 'left') {
            $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 6,'sort' => 'pid ASC','filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)));
            $imagesAjax = array_reverse($imagesAjax);
        } else $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid),'limit' => 6,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)));        
           
    } elseif ($modeSort == 'newasc') {  
        
        if ($direction == 'left') {      
            $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 6,'sort' => 'pid DESC','filter' => array('aid' => $Image->aid)+(array)$filterArray,'filterlt' => array('pid' => $Image->pid)));
            $imagesAjax = array_reverse($imagesAjax);
        } else $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('sort' => 'pid ASC','cache_key' => 'album_image_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$Image->aid).$modeSort,'limit' => 6,'filter' => array('aid' => $Image->aid)+(array)$filterArray,'filtergt' => array('pid' => $Image->pid)));        
        
    } elseif ($modeSort == 'popular') {

        
        $cache = CSCacheAPC::getMem();         
        $cacheKeyImage = 'album_mode_image_ajaxslides_pid_sort_popular_'.$Image->pid.'_popular_version_'.$cache->getCacheVersion('most_popular_version',time(),1500).'_version_'.$cache->getCacheVersion('album_'.$Image->aid).'_album_id_'.$Image->aid.'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
                        
        if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
        {               
            $session = erLhcoreClassGallery::getSession();
            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
            
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                    
            if ($direction == 'left') {  
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('hits ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                $imagesAjax = array_reverse($imagesAjax);   
            } else {            
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('hits DESC, pid DESC')
                ->limit( 6 );
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
                    
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );    
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') { 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('hits DESC, pid DESC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' ); 
                $imagesAjax = array_reverse($imagesAjax);
            } else {               
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('hits ASC, pid ASC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {                   
                
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('mtime ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {            
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('mtime DESC, pid DESC')
                ->limit( 6 );
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
             
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );             
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {                   
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ( '.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('mtime DESC, pid DESC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {    
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('mtime ASC, pid ASC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );             
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }       
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') { 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('comtime ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else { 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('comtime DESC, pid DESC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = '';  
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );   
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {       
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('comtime DESC, pid DESC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {                 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('comtime ASC, pid ASC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = '';
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );             
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }       
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') { 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('rtime ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else { 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('rtime DESC, pid DESC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = '';  
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );   
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {       
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('rtime DESC, pid DESC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
            } else {                 
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'rtime', $q->bindValue( $Image->rtime ) ). ' OR '.$q->expr->eq( 'rtime', $q->bindValue( $Image->rtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ) .')')
                ->orderBy('rtime ASC, pid ASC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );           
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
                     
            if ($direction == 'left') {    
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating ASC, votes ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax); 
            } else {              
                $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating DESC, votes DESC, pid DESC')
                ->limit( 6 );
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
            
            $filterSQLArray = array(); 
            $filterSQLString = ''; 
            $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );            
            if ($resolution != '') {
               $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
               $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );           
            }
            $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
            
            
            if ($direction == 'left') {  
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating DESC, votes DESC, pid DESC')
            ->limit( 6 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);   
             } else {         
            $q->where( $filterSQLString.$q->expr->eq( 'aid', $q->bindValue( $Image->aid ) ).' AND ('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
            ->orderBy('pic_rating ASC, votes ASC, pid ASC')
            ->limit( 6 );
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
       
    if ($modeSort == 'relevance') {
                    
        $relevanceCurrentImage = erLhcoreClassGallery::searchSphinx(array('relevance' => true, 'SearchLimit' => 1,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC','Filter' => array('@id' => $Image->pid)));
           
        if ($direction == 'left') {
                
            $totalPhotos = erLhcoreClassGallery::searchSphinx(array('filtergt' => array('pid' => $Image->pid),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('filtergt' => array('@weight' => $relevanceCurrentImage),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
                            
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
        	
        	$totalPhotos = erLhcoreClassGallery::searchSphinx(array('filterlt' => array('pid' => $Image->pid-1),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('filterlt' => array('@weight' => $relevanceCurrentImage-1),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
                                     
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
                    
        $relevanceCurrentImage = erLhcoreClassGallery::searchSphinx(array('relevance' => true, 'SearchLimit' => 1,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC','Filter' => array('@id' => $Image->pid)));
           
        if ($direction == 'left') {
                
            $totalPhotos = erLhcoreClassGallery::searchSphinx(array('filterlt' => array('pid' => $Image->pid-1),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('filterlt' => array('@weight' => $relevanceCurrentImage-1),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance DESC, @id DESC'));
                            
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
        	
        	$totalPhotos = erLhcoreClassGallery::searchSphinx(array('filtergt' => array('pid' => $Image->pid),'Filter' => (array)$filterArray+array('@weight' => $relevanceCurrentImage),'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
            
            if ($totalPhotos['total_found'] < 6) { // We have check is there any better matches images on left
                $totalPhotosHigher = erLhcoreClassGallery::searchSphinx(array('filtergt' => array('@weight' => $relevanceCurrentImage),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@relevance ASC, @id ASC'));
                                     
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
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 6,'keyword' =>  urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','Filter' => $filterArray,'filtergt' => array('pid' => $Image->pid)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 6,'keyword' =>  urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','Filter' => $filterArray,'filterlt' => array('pid' => $Image->pid-1)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}  
                  
    } elseif ($modeSort == 'newasc') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id DESC','Filter' => $filterArray,'filterlt' => array('pid' => $Image->pid-1)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => '@id ASC','Filter' => $filterArray,'filtergt' => array('pid' => $Image->pid)));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}    	
             
    } elseif ($modeSort == 'popular') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos =  erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	 
              
    } elseif ($modeSort == 'popularasc') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits < '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (hits > '.$Image->hits.' OR (hits = '.$Image->hits.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'hits ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}  
         
    } elseif ($modeSort == 'lasthits') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	} 
        
    } elseif ($modeSort == 'lasthitsasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime < '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (mtime > '.$Image->mtime.' OR (mtime = '.$Image->mtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'mtime ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	              
    } elseif ($modeSort == 'lastcommented') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
             
    } elseif ($modeSort == 'lastcommentedasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime < '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid < '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (comtime > '.$Image->comtime.' OR (comtime = '.$Image->comtime.' AND pid > '.$Image->pid.')) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'comtime ASC, @id ASC'));        
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	               
    } elseif ($modeSort == 'toprated') {
                
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];	      
    	   endif;
    	}
    	            
    } elseif ($modeSort == 'topratedasc') {
        
        if ($direction == 'left'){
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating < '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes < '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid < '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating DESC, votes DESC, @id DESC'));
    	   if ($totalPhotos['total_found'] > 0):
    	       $imagesAjax = $totalPhotos['list'];
    	       $imagesAjax = array_reverse($imagesAjax);    
    	   endif;
    	} else {
    	   $totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, (pic_rating > '.$Image->pic_rating.' OR (pic_rating = '.$Image->pic_rating.' AND votes > '.$Image->votes.') OR (pic_rating = '.$Image->pic_rating.' AND votes = '.$Image->votes.' AND pid > '.$Image->pid.' )  ) AS myfilter'),'Filter' => $filterArray,'SearchLimit' => 6,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']),'sort' => 'pic_rating ASC, votes ASC, @id ASC'));
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
    $urlAppend .= $appendResolutionMode;
     
    $tpl->set('urlAppend',$urlAppend);  
    
} elseif ($mode == 'lastuploads') {
      
	if ($direction == 'left'){
	   $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'filter' => $filterArray,'limit' => 6,'sort' => 'pid ASC','filtergt' => array('pid' => $Image->pid)));
	   rsort($imagesAjax);   
	} else {
	   $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'filter' => $filterArray,'limit' => 6,'sort' => 'pid DESC','filterlt' => array('pid' => $Image->pid))); 
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
    
    	 $filterSQLArray = array(); 
         $filterSQLString = '';
         
         $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );        
         if ($resolution != '') {
            $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );
         }
         
    	 $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
         
    	if ($direction == 'left'){
    	   $q->where( $filterSQLString.'('.$q->expr->gt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 6 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	   $q->where( $filterSQLString.'('.$q->expr->lt( 'mtime', $q->bindValue( $Image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $Image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 6 );
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
    	
    	 $filterSQLArray = array(); 
         $filterSQLString = '';  
         $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );   
         if ($resolution != '') {
            $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );
         }
              
    	 $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
              
    	if ($direction == 'left'){
    	    $q->where( $filterSQLString.'('.$q->expr->gt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 6 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	    $q->where( $filterSQLString.'('.$q->expr->lt( 'hits', $q->bindValue( $Image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $Image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
            ->orderBy('hits DESC, pid DESC')
            ->limit( 6 );
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
    	
    	$filterSQLArray = array(); 
        $filterSQLString = ''; 
        $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );   
        if ($resolution != '') {
            $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
            $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );        
        }
        $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
          	
    	if ($direction == 'left'){
    	    $q->where(  $filterSQLString.'('.$q->expr->gt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('comtime ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	       $q->where(  $filterSQLString.'('.$q->expr->lt( 'comtime', $q->bindValue( $Image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $Image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')' )
                ->orderBy('comtime DESC, pid DESC')
                ->limit( 6 );
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
	
} elseif ($mode == 'toprated') {

    $cache = CSCacheAPC::getMem();         
    $cacheKeyImage = 'toprated_mode_image_ajaxslides_pid_'.$Image->pid.'_version_'.$cache->getCacheVersion('top_rated').'_direction_'.$direction.'_filter_'.erLhcoreClassGallery::multi_implode(',',$filterArray);
    
    if (($imagesAjax = $cache->restore($cacheKeyImage)) === false)
    {      	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
    	
    	$filterSQLArray = array(); 
        $filterSQLString = '';
        $filterSQLArray[] = $q->expr->eq( 'approved', $q->bindValue( 1 ) );    
        if ($resolution != '') {
           $filterSQLArray[] = $q->expr->eq( 'pwidth', $q->bindValue( $resolutions[$resolution]['width'] ) );
           $filterSQLArray[] = $q->expr->eq( 'pheight', $q->bindValue( $resolutions[$resolution]['height'] ) );
        }
        $filterSQLString = implode(' AND ',$filterSQLArray).' AND ';
              	
    	if ($direction == 'left'){
    	     $q->where( $filterSQLString.'('.$q->expr->gt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.
                $q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating ASC, votes ASC, pid ASC')
                ->limit( 6 );
                $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	       $q->where( $filterSQLString.'('.$q->expr->lt( 'pic_rating', $q->bindValue( $Image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $Image->votes ) ).' OR '.
                $q->expr->eq( 'pic_rating', $q->bindValue( $Image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $Image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $Image->pid ) ).')')
                ->orderBy('pic_rating DESC, votes DESC, pid DESC')
                ->limit( 6 );
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