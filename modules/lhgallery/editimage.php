<?php

$Image = $Params['user_object'];

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/editimage.tpl.php');

$tpl->set('image',$Image);

$Result['content'] = $tpl->fetch();

$Album = $Image->album;
$path = array();
$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','Home')); 
$pathObjects = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$Album->category);   
$pathCategorys = array();      
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name);
   $pathCategorys[] = $pathItem->cid; 
}
$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$Album->aid,'title' => $Album->title);
$path[] = array('title' => $Image->name_user);
$Result['path'] = $path;