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

$cacheKey = md5('version_'.$cache->getCacheVersion('last_commented').'lastcommented_view_url'.$resolution.'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
    
if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'etag_caching_enabled' ) === true)
{
    $ExpireTime = 3600;
    $currentKeyEtag = md5($cacheKey.'user_id_'.erLhcoreClassUser::instance()->getUserID());
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
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lastcommented.tpl.php');
        
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('filter' => $filterArray));
    $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/lastcommented').$appendResolutionMode;
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $tpl->set('currentResolution',$resolution);
    $tpl->set('filterArray',$filterArray);
    
    $appendImageMode = '/(mode)/lastcommented'.$appendResolutionMode;
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/lastcommented'));
        
    $Result['content'] = $tpl->fetch();
    $Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/lastcommented').$appendResolutionMode.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : '');        
    $Result['path'] = array();    
    $Result['path'][] = array('url' =>erLhcoreClassDesign::baseurl('gallery/lastcommented'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Last commented images'));
     
    if ($resolution != '') {
        $Result['path'][] = array('url' =>erLhcoreClassDesign::baseurl('gallery/lastcommented').$appendResolutionMode,'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Resolution').' - '.$resolution);  
    }
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
      
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Last commented images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/lastcommentedrss');
    $Result['description_prepend'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Last commented gallery images.');
    

    $cache->store($cacheKey,$Result);
}



?>