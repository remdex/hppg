<?php

try {
    $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/showimageinfo.tpl.php');
$tpl->set('image',$Image);

echo json_encode(array('result' =>$tpl->fetch()));
exit;