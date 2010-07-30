<?php

try {
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/sharehtml.tpl.php');
$tpl->set('Image',$Image);
echo $tpl->fetch();

exit;