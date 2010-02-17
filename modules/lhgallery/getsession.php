<?php

$AlbumData = $Params['user_object'] ;

$fileSession = new erLhcoreClassModelGalleryUpload();
$fileSession->album_id = $AlbumData->aid;	
$fileSession->created = time();	
$fileSession->hash = sha1(time().mt_rand(1,100));	

$currentUser = erLhcoreClassUser::instance();
if ($currentUser->isLogged())
    $fileSession->user_id = $currentUser->getUserID();	
else 
    $fileSession->user_id = 0;	
                
erLhcoreClassGallery::getSession()->save($fileSession);
                  
echo json_encode(array('error' => 'false','sessionhash' => $fileSession->hash));
   
exit;