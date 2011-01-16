<?php

$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('lastuploadstoalbums_site_version'.$cache->getCacheVersion('site_version').'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
    
if (($Result = $cache->restore($cacheKey)) === false)
{
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lastuploadstoalbums.tpl.php');    
        
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true));
    $pages->setItemsPerPage(20);
    $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbums');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
        
    $Result['content'] = $tpl->fetch();
    
    $path = array();
    $Result['path'] = array(array('title' => 'Last uploads to albums','url' => erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbums')));
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/category','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $cache->store($cacheKey,$Result);
} 

?>