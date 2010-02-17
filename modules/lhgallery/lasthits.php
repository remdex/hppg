<?php

$cache = CSCacheAPC::getMem(); 

// All cache will expire at once, subpages expires every 10 minits.
// Total image expiration  
$cacheVersion = $cache->getCacheVersion('last_hits_version',time(),600);

if (($Result = $cache->restore(md5($cacheVersion.'_lasthits_view_url'.'_page_'.$Params['user_parameters_unordered']['page']))) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lasthits.tpl.php');
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount();
    $pages->translationContext = 'gallery/lasthits';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/lasthits');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Last viewed images')));    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthits','Last viewed images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/lasthitsrss/');

    $cache->store(md5($cacheVersion.'_lasthits_view_url'.'_page_'.$Params['user_parameters_unordered']['page']),$Result);
}

?>