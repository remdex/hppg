<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/albumlistdirectory.tpl.php');


if (is_writable(base64_decode(urldecode($Params['user_parameters']['directory'])))) {
	$writable = true;
    $tpl->set('writable',$writable);        
} else {
	$writable = false;
    $tpl->set('writable',$writable);
}


if ($Params['user_parameters']['recursive'] != 'true')
	$tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectory(base64_decode(urldecode($Params['user_parameters']['directory'])),true));
else 
	$tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectoryRecursive(base64_decode(urldecode($Params['user_parameters']['directory']))));

	
echo json_encode(array('result' => $tpl->fetch(),'is_writable' => $writable));
exit;