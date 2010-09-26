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
    $pages->translationContext = 'gallery/lasthits';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/popularrecent');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $tpl->set('filterArray',$filterArray);
    
    $appendImageMode = '/(mode)/popularrecent';
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('/gallery/popularrecent'));
        
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Most popular images in 24 hours')));    
  
    $cache->store($cacheKey,$Result);
}