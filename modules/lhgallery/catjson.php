<?php
	$masyvas = array();
	$albums = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 1000,'filter' => array('category' => (int)$_GET['id'])));
	foreach($albums as $album) {
		$masyvas[] = array("id" => $album->aid, "name" => $album->title, "type" => 1);
	}
	$cats = erLhcoreClassModelGalleryCategory::getParentCategories((int)$_GET['id']);
	foreach($cats as $cat) {
		$subalbums = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 1000,'filter' => array('category' => $cat->cid)));
		$subcats = erLhcoreClassModelGalleryCategory::getParentCategories($cat->cid);
		$masyvas[] = array("id" => $cat->cid, "name" => $cat->name, "type" => 2, "haschild" => ((count($subalbums)+count($subcats)) > 0 ? 1 : 0));
	}
	echo json_encode($masyvas);
	exit;
?>