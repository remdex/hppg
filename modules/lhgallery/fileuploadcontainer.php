<?php

$AlbumData = $Params['user_object'] ;

$FileID = $Params['user_parameters']['fileID'];
$FileName = $_POST['filename'];

$error = false;
$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/fileuploadcontainer.tpl.php');
$tpl->set('fileID',$FileID);
$tpl->set('fileName',$FileName);

$currentUser = erLhcoreClassUser::instance();
if (!file_exists('albums/userpics/'.$currentUser->getUserID().'/'.$AlbumData->aid.'/'.erLhcoreClassImageConverter::sanitizeFileName($FileName))) {
    $tpl->set('supported',true);    
} else {
    $tpl->set('supported',false);
    $error = true;
}

echo json_encode(array('result' => $tpl->fetch(),'error' => $error));
exit;

?>