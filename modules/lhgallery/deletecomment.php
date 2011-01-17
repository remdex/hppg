<?php

//echo (int)$Params['user_parameters']['comment_id'] ;exit;

try {
        $Comment = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryComment', (int)$Params['user_parameters']['comment_id'] );
        
    } catch (Exception $e){
        print_r($e);
//        erLhcoreClassModule::redirect('/');
//        exit;
}

$Comment->removeThis();

echo json_encode(array('error' => 'false'));
exit;