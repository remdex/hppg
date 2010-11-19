<?php

$cache = CSCacheAPC::getMem(); 


$cacheVersion = $cache->getCacheVersion('ratedrecent_version');
$cacheKey = md5($cacheVersion.'_ratedrecent_view_url_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);

if (($Result = $cache->restore($cacheKey)) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/ratedrecent.tpl.php');
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryRated24::getImageCount();
    $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/ratedrecent');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    
    $appendImageMode = '/(mode)/ratedrecent';
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/ratedrecent'));
        
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratedrecent','Top rated images in 24 hours')));    
  
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratedrecent','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $cache->store($cacheKey,$Result);
}