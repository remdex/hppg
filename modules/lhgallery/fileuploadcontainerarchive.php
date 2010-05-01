<?php

$FileID = $Params['user_parameters']['fileID'];
$FileName = $_POST['filename'];

$error = false;
$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/fileuploadcontainerarchive.tpl.php');
$tpl->set('fileID',$FileID);
$tpl->set('fileName',$FileName);

$tpl->set('supported',true);    

echo json_encode(array('result' => $tpl->fetch(),'error' => $error));
exit;

?>