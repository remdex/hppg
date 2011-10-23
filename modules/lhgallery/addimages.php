<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/addimages.tpl.php');
$AlbumData = $Params['user_object'];

$tpl->set('album',$AlbumData);
$Result['content'] = $tpl->fetch();
$Result['album_id'] = $AlbumData->aid;
$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/fileuploader.js').'"></script>';