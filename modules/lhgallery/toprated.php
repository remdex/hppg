<?php

$cache = CSCacheAPC::getMem(); 

$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' ); 
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';

$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
$filterArray = array();

if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
}
$filterArray['approved'] = 1;

$cacheKey = md5('version_'.$cache->getCacheVersion('top_rated').'_topratedalbum_view_url_'.$resolution.'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
    
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'etag_caching_enabled' ) === true)
{   
    $currentKeyEtag = md5($cacheKey.'user_id_'.erLhcoreClassUser::instance()->getUserID());;
    header('Cache-Control: must-revalidate'); // must-revalidate
	header('ETag: ' . $currentKeyEtag);
    
    $iftag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] == $currentKeyEtag : null;         
    if ($iftag === true)
    {   
        header ("HTTP/1.0 304 Not Modified");
        header ('Content-Length: 0');
        exit;
    }
} 
if (($Result = $cache->restore($cacheKey)) === false)
{  
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/toprated.tpl.php');
    
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true,'filter' => $filterArray));
    $pages->translationContext = 'gallery/toprated';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/toprated').$appendResolutionMode;
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $tpl->set('currentResolution',$resolution);
    $tpl->set('filterArray',$filterArray);
    
    $appendImageMode = '/(mode)/toprated'.$appendResolutionMode;
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('/gallery/toprated'));
    
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','Top rated images')));    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','Top rated images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/topratedrss/');

    $cache->store($cacheKey,$Result,0);

}



?>