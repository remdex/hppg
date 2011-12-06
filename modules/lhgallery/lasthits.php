<?php

$cache = CSCacheAPC::getMem(); 


$resolutions = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'resolutions' ); 
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';

$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
$filterArray = array();

if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
}
$filterArray['approved'] = 1;

// All cache will expire at once, subpages expires every 10 minits.
// Total image expiration  
$cacheVersion = $cache->getCacheVersion('last_hits_version',time(),600);
$cacheKey = md5($cacheVersion.'_lasthits_view_url'.$resolution.'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);

if (($Result = $cache->restore($cacheKey)) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lasthits.tpl.php');
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('filter' => $filterArray));
    $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/lasthits').$appendResolutionMode;
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $tpl->set('currentResolution',$resolution);
    $tpl->set('filterArray',$filterArray);
    
    $appendImageMode = '/(mode)/lasthits'.$appendResolutionMode;
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/lasthits'));
    
    $Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/lasthits').$appendResolutionMode.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : ''); 
    $Result['content'] = $tpl->fetch();
    
    $Result['path'] = array();
    
    $Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('gallery/lasthits'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Last viewed images'));  
    
    if ($resolution != '') {
        $Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('gallery/lasthits').$appendResolutionMode,'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Resolution').' - '.$resolution);  
    }
      
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('gallery/lasthits').'/(page)/'.$Params['user_parameters_unordered']['page'], 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Last viewed images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/lasthitsrss');
    $Result['description_prepend'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Last viewed gallery images.');
    
    $cache->store($cacheKey,$Result);
}