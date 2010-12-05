<?php

$ImageData = $Params['user_object'] ;

$userOwner = erLhcoreClassUser::instance();
$canApproveSelfImages = erLhcoreClassUser::instance()->hasAccessTo('lhgallery','can_approve_self_photos');
$canApproveAllImages = erLhcoreClassUser::instance()->hasAccessTo('lhgallery','can_approve_all_photos');
$canChangeApprovement = ($ImageData->owner_id == $userOwner->getUserID() && $canApproveSelfImages) || ($canApproveAllImages == true);

$ImageData->title = isset($_POST['title']) ? $_POST['title'] : '';
$ImageData->keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
$ImageData->caption = isset($_POST['caption']) ? $_POST['caption'] : '';
$ImageData->anaglyph =  (isset($_POST['anaglyph']) && $_POST['anaglyph'] == 'true') ? 1 : 0;

$previousApproved = $ImageData->approved;
$ImageData->approved =  $canChangeApprovement == true ? ((isset($_POST['approved']) && $_POST['approved'] == 'true') ? 1 : 0) : $ImageData->approved;

erLhcoreClassGallery::getSession()->update($ImageData); 
$ImageData->clearCache();

// Changed approvement status we have to clear all cache.
if ($previousApproved != $ImageData->approved){
    CSCacheAPC::getMem()->increaseImageManipulationCache();
    
    // Expires last uploads shard index
    erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_uploads','last_commented'));
}


echo json_encode(array('error' => 'false','result' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/updateimage','Image updated')));
exit;