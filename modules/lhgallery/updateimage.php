<?php

$ImageData = $Params['user_object'] ;

$ImageData->title = isset($_POST['title']) ? $_POST['title'] : '';
$ImageData->keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
$ImageData->caption = isset($_POST['caption']) ? $_POST['caption'] : '';
$ImageData->anaglyph =  (isset($_POST['anaglyph']) && $_POST['anaglyph'] == 'true') ? 1 : 0;

erLhcoreClassGallery::getSession()->update($ImageData); 
$ImageData->clearCache();

echo json_encode(array('error' => 'false','result' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/updateimage','Image updated')));
exit;