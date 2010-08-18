<?php

	$CategoryID = (int)$Params['user_parameters']['category_id'];
	
	$masyvas = array();
	$albums = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 1000,'filter' => array('category' => $CategoryID)));
	foreach($albums as $album) {
		$masyvas[] = array("id" => $album->aid, "name" => $album->title, "type" => 1);
	}
	$cats = erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => $CategoryID),'disable_sql_cache' => true,'use_iterator' => true,'limit' => 1000000));
	$cache = CSCacheAPC::getMem();
	
	// FIXME make appropriate fetch for albums count
	foreach($cats as $cat) {
		$subalbums = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 1000,'filter' => array('category' => $cat->cid)));
		$subcats = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('parent' => $cat->cid),'cache_key' => 'version_'.$cache->getCacheVersion('category_'.$cat->cid)));
		$masyvas[] = array("id" => $cat->cid, "name" => $cat->name, "type" => 2, "haschild" => ((count($subalbums)+$subcats) > 0 ? 1 : 0));
	}
	echo json_encode($masyvas);
	exit;
?>