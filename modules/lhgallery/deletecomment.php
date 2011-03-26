<?php
try {
        $Comment = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryComment', (int)$Params['user_parameters']['comment_id'] );        
    } catch (Exception $e){
    exit;
}

$Comment->removeThis();

echo json_encode(array('error' => 'false'));
exit;