<?php

try {    
// Simple is it? :)
$image = $Params['user_object'];
$album = $image->album;
$album->album_pid = $image->pid;
$album->updateThis();
echo json_encode(array('error' => 'true','result' => 'Done :)'));

} catch (Exception $e){
    echo json_encode(array('error' => 'true','result' => (string)$e));
}

exit;
