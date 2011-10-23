<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/addimagesadmin.tpl.php');
$AlbumData = $Params['user_object'];


$tpl->set('album',$AlbumData);
$Result['content'] = $tpl->fetch();
$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/fileuploader.js').'"></script>';

$pathObjects = array();
$pathCategorys = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$AlbumData->category);   
$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/managealbumimages'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumeditadmin','Root category'));     
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name); 
   $pathCategorys[] = $pathItem->cid; 
}

$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$AlbumData->aid,'title' => $AlbumData->title);

$path[] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumeditadmin','Add images'));


$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;
$Result['album_id'] = $AlbumData->aid;