<?php

$cache = CSCacheAPC::getMem(); 

// All cache will expire at once, subpages expires every 10 minits.
// Total image expiration  
$cacheVersion = $cache->getCacheVersion('popularrecent_version',time(),600);
$cacheKey = md5($cacheVersion.'_popularrecent_view_url_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);

if (($Result = $cache->restore($cacheKey)) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/popularrecent.tpl.php');
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryPopular24::getImageCount();
    $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/popularrecent');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    
    $appendImageMode = '/(mode)/popularrecent';
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/popularrecent'));
        
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popularrecent','Most popular images in 24 hours')));    
  
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popularrecent','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $cache->store($cacheKey,$Result);
}