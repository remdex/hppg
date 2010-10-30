<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/addimagesadmin.tpl.php');
$AlbumData = $Params['user_object'];


$tpl->set('album',$AlbumData);
$Result['content'] = $tpl->fetch();

$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/swfupload.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.swfobject.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.cookies.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.queue.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.speed.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/fileprogress.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/handlers.js').'"></script>';

$pathObjects = array();
$pathCategorys = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$AlbumData->category);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
   $pathCategorys[] = $pathItem->cid; 
}

$path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/managealbumimages/').$AlbumData->aid,'title' => $AlbumData->title);

$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;
$Result['album_id'] = $AlbumData->aid;