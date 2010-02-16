<?php

if (isset($_POST['photo']) && isset($_POST['score']) )
{ 
    $rate = min($_POST['score'], 5);
    $rate = max($rate, 0);

    $image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$_POST['photo'] );    
    $new_rating = round(($image->votes * $image->pic_rating + (int)$rate * 2000) / ($image->votes + 1));    
    $image->pic_rating = $new_rating;
    $image->votes = $image->votes + 1;
    $image->sort_rated = $image->pic_rating*$image->votes;
        
    erLhcoreClassGallery::getSession()->update($image);
    
    //Clear top rated listing cache
    CSCacheAPC::getMem()->increaseCacheVersion('top_rated');
    
    echo json_encode(array('result' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/addvote','Thank you for your vote'),'error' => 'false'));
    exit;      
}

?>