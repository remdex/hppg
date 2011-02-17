<?php

try {
    $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/showimageinfo.tpl.php');
$tpl->set('image',$Image);
$tpl->set('sort',urldecode($Params['user_parameters']['sort']));

echo json_encode(array('result' =>$tpl->fetch()));
exit;