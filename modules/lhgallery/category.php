<?php


$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('category_'.(int)$Params['user_parameters']['category_id']).'category_view_url'.(int)$Params['user_parameters']['category_id'].'_page_'.$Params['user_parameters_unordered']['page']);
    
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
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/category.tpl.php');    
    try {
        $Category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$Params['user_parameters']['category_id'] );
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
    } 
    
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true,'filter' => array('category' => $Category->cid)));
    $pages->translationContext = 'gallery/album';
    $pages->default_ipp = 8;
    $pages->serverURL = $Category->path_url;
    $pages->paginate();
    
    $tpl->set('pagesCurrent',$pages);
    $tpl->set('category',$Category);
    
    $Result['content'] = $tpl->fetch();
    $path = array();
    erLhcoreClassModelGalleryCategory::getCategoryPath($path,$Category->cid);
    $Result['path'] = $path;
    $cache->store($cacheKey,$Result);
} 

?>