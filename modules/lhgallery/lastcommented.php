<?php

$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('last_commented').'lastcommented_view_url'.'_page_'.$Params['user_parameters_unordered']['page']);
    
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'etag_caching_enabled' ) === true)
{
    $ExpireTime = 3600;
    $currentKeyEtag = md5($cacheKey.'user_id_'.erLhcoreClassUser::instance()->getUserID());
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
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lastcommented.tpl.php');
    
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true));
    $pages->translationContext = 'gallery/lastcommented';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/lastcommented');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Last commented images')));    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Last commented images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/lastcommentedrss/');

    $cache->store($cacheKey,$Result);
}

?>