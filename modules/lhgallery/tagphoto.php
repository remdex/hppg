<?php

if (isset($_POST['photo']) && isset($_POST['tags']) && $_POST['tags'] != '')
{     
    $image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$_POST['photo'] );   
    $image->keywords = $image->keywords.' '.$_POST['tags'];            
    erLhcoreClassGallery::getSession()->update($image); 
}

echo json_encode(array('result' => 'Thank you a lot :)','error' => 'false'));
exit;

?>