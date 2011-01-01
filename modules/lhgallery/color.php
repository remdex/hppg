<?php

$pallete_id = (array)$Params['user_parameters_unordered']['color'];

$cache = CSCacheAPC::getMem(); 

sort($pallete_id);

$cacheKey = md5('version_'.$cache->getCacheVersion('color_images').'_color_view_url_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_color_id_'.erLhcoreClassGallery::multi_implode(',',$pallete_id));

if (($Result = $cache->restore($cacheKey)) === false)
{
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/color.tpl.php');
    $tpl->set('show_pallete',true);
    $pallete_items_number = count($pallete_id);
    
    if ($pallete_items_number > 0) {    
        try {                
                  
            if ($pallete_items_number > erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters')) {
                $pallete_id = array_slice($pallete_id,0,erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));
                $pallete_items_number = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');
            }
            sort($pallete_id);
            $tpl->set('max_filters',$pallete_items_number == erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));    
            $tpl->set('show_pallete',false);    
            $pages = new lhPaginator();
            
            $list = array();
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'database_handler') == true) {
                $pages->items_total = erLhcoreClassModelGalleryPallete::getListCountPalleteImages(array('pallete_id' => $pallete_id));
                if ($pages->items_total > 0){
                    $list = erLhcoreClassModelGalleryPallete::getImages(array('pallete_id' => $pallete_id,'sort' => 'lh_gallery_pallete_images.count DESC, lh_gallery_pallete_images.pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
                }
            } else {
                $searchParams = array();
                $searchParams['color_filter'] = $pallete_id;         
                $searchParams['SearchLimit'] = 20;
                $searchParams['SearchOffset'] = $pages->low;
                      
                $standardSearch = count($pallete_id) == 1 || erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'extended_search') == false;
                
                if ($standardSearch == true) {
                    $searchParams['sort'] = '@relevance DESC, @id DESC';
                } else {
                    $searchParams['sort'] = 'custom_match DESC @id DESC';
                }
                
                $searchParams['color_search_mode'] = true;
                
                $searchResult = erLhcoreClassGallery::searchSphinx($searchParams,false);
                $list = $searchResult['list'];
                $pages->items_total =  $searchResult['total_found'];
            }
            
            $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/color').'/(color)/'.implode('/',$pallete_id);
            $pages->paginate();
            $tpl->set('pages',$pages);
            $tpl->set('items',$list);
            $tpl->set('appendImageMode','/(mode)/color/(color)/'.implode('/',$pallete_id));
            $tpl->set('pallete_id',$pallete_id);
            $tpl->set('palletes',erLhcoreClassModelGalleryPallete::getList(array('filterin' => array('id' => $pallete_id))));
            $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/color').'/'.$pallete_id);                  
        } catch (Exception $e) {
            
        }
    }
    
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Images by color')));    
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $cache->store($cacheKey,$Result);
}

