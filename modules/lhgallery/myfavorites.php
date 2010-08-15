<?php

$favouriteSession = erLhcoreClassModelGalleryMyfavoritesSession::getInstance();
$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('favorite_'.(int)$favouriteSession->id).'favorite_view_url'.(int)$favouriteSession->id.'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);

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
	$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/myfavorites.tpl.php');
	
	$pages = new lhPaginator();
	$pages->items_total = erLhcoreClassModelGalleryMyfavoritesImage::getImageCount(array('filter' => array('session_id' => $favouriteSession->id)));
	$pages->translationContext = 'gallery/album';
	$pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/myfavorites');
	$pages->paginate();
	
	$tpl->set('pages',$pages);
	$tpl->set('session',$favouriteSession);
	
	$Result['content'] = $tpl->fetch();
	
	$Result['path'] = array(
	array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','My favorite images')));
	
	$cache->store($cacheKey,$Result);	
    
}

