<?php

$fileSession = new erLhcoreClassModelGalleryUpload();
$fileSession->album_id = 0;	
$fileSession->created = time();	
$fileSession->hash = sha1(time().mt_rand(1,100));	

$currentUser = erLhcoreClassUser::instance();
if ($currentUser->isLogged())
    $fileSession->user_id = $currentUser->getUserID();	
else 
    $fileSession->user_id = erConfigClassLhConfig::getInstance()->conf->getSetting( 'user_settings', 'anonymous_user_id' );	
                
erLhcoreClassGallery::getSession()->save($fileSession);
                  
echo json_encode(array('error' => 'false','sessionhash' => $fileSession->hash));
   
exit;