<?php

$pallete_id = (array)$Params['user_parameters_unordered']['color'];
$npallete_id = (array)$Params['user_parameters_unordered']['ncolor'];

$cache = CSCacheAPC::getMem(); 
sort($pallete_id);
sort($npallete_id);

$searchParams = array();
$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';
$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
if ($resolution != ''){
    $searchParams['Filter']['pwidth'] = $resolutions[$resolution]['width'];
    $searchParams['Filter']['pheight'] = $resolutions[$resolution]['height'];
}

$cacheKey = md5('version_'.$cache->getCacheVersion('color_images').'_color_view_url_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_color_id_'.erLhcoreClassGallery::multi_implode(',',$pallete_id).erLhcoreClassGallery::multi_implode(',',$searchParams).erLhcoreClassGallery::multi_implode(',',$npallete_id));

if (($Result = $cache->restore($cacheKey)) === false)
{
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/color.tpl.php');
    $tpl->set('show_pallete',true);
    $pallete_items_number = count($pallete_id);
    $npallete_items_number = count($npallete_id);
    
    
    if ( $pallete_items_number > 0 || $npallete_items_number > 0 ) {    
        try {                
                  
            if ($pallete_items_number > erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters')) {
                $pallete_id = array_slice($pallete_id,0,erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));
                $pallete_items_number = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');
            } 
                
            if ($npallete_items_number > erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters')) {
                $npallete_id = array_slice($npallete_id,0,erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));
                $npallete_items_number = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');
            }
            
            sort($pallete_id);
            sort($npallete_id);
            
            $tpl->set('max_filters',$pallete_items_number == erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));  
            $tpl->set('nmax_filters',$npallete_items_number == erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));  
              
            $tpl->set('show_pallete',false);    
            $pages = new lhPaginator();
            
             
            
            $list = array();
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'database_handler') == true) {
                $pages->items_total = erLhcoreClassModelGalleryPallete::getListCountPalleteImages(array('pallete_id' => $pallete_id));
                if ($pages->items_total > 0){
                    $list = erLhcoreClassModelGalleryPallete::getImages(array('pallete_id' => $pallete_id,'sort' => 'lh_gallery_pallete_images.count DESC, lh_gallery_pallete_images.pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
                }
            } else {
                
                
                $searchParams['color_filter'] = $pallete_id; 
                $searchParams['ncolor_filter'] = $npallete_id; 
                        
                $searchParams['SearchLimit'] = 20;
                $searchParams['SearchOffset'] = $pages->low;
                      
                $standardSearch = count($pallete_id) == 1 || erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'extended_search') == false;
                
                if ($standardSearch == true || empty($pallete_id) ) {
                    $searchParams['sort'] = '@relevance DESC, @id DESC';
                } else {
                    $searchParams['sort'] = 'custom_match DESC @id DESC';
                }
                
                $searchParams['color_search_mode'] = true;
                
                $searchResult = erLhcoreClassGallery::searchSphinx($searchParams,false);
                $list = $searchResult['list'];
                $pages->items_total =  $searchResult['total_found'];
            }
            
            
            
            $colorURL = '';
            $yesColor = '';
            $noColor = '';
            
            if ($pallete_items_number > 0)
            {
                $yesColor = '/(color)/'.implode('/',$pallete_id);
                $colorURL .= $yesColor;
            }
            
            if ($npallete_items_number > 0)
            {
                $noColor = '/(ncolor)/'.implode('/',$npallete_id);
                $colorURL .= $noColor;
            }
            
            $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/color').$colorURL.$appendResolutionMode;
            
            $pages->paginate();
            $tpl->set('pages',$pages);
            $tpl->set('items',$list);
            $tpl->set('appendImageMode','/(mode)/color'.$colorURL.$appendResolutionMode); 
            $tpl->set('appendResolutionMode',$appendResolutionMode);
            $tpl->set('currentResolution',$resolution);
            $tpl->set('pallete_id',$pallete_id);
            $tpl->set('npallete_id',$npallete_id);
            
            $tpl->set('yes_color',$yesColor);
            $tpl->set('no_color',$noColor);
                       
            $tpl->set('database_handler',erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'database_handler'));
            
            if (!empty($pallete_id))
            $tpl->set('palletes',erLhcoreClassModelGalleryPallete::getList(array('filterin' => array('id' => $pallete_id))));
            
            if (!empty($npallete_id))
            $tpl->set('npalletes',erLhcoreClassModelGalleryPallete::getList(array('filterin' => array('id' => $npallete_id))));
            
            
            $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/color').$colorURL);  
            $Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/color').$colorURL.$appendResolutionMode.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : '');
                       
           
            
        } catch (Exception $e) {
            
        }
    } else {
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/color');
    }
    
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Images by color')));    
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $cache->store($cacheKey,$Result);
}

