<?php

$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('top_rated').'_topratedalbum_view_url_page_'.$Params['user_parameters_unordered']['page']);
    
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'etag_caching_enabled' ) === true)
{
    $ExpireTime = 3600;
    $currentKeyEtag = md5($cacheKey.'user_id_'.erLhcoreClassUser::instance()->getUserID());;
    header('Cache-Control: max-age=' . $ExpireTime); // must-revalidate
    header('Expires: '.gmdate('D, d M Y H:i:s', time()+$ExpireTime).' GMT');
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
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true));
    $pages->translationContext = 'gallery/toprated';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/toprated');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','Top rated images')));    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','Top rated images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/topratedrss/');

    $cache->store($cacheKey,$Result,0);

}



?>