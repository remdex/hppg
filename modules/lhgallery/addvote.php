<?php

if (isset($_POST['photo']) && isset($_POST['score']) )
{ 
    $rate = min($_POST['score'], 5);
    $rate = max($rate, 0);

    $image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$_POST['photo'] );    
    $new_rating = round(($image->votes * $image->pic_rating + (int)$rate * 2000) / ($image->votes + 1));    
    $image->pic_rating = $new_rating;
    $image->votes = $image->votes + 1;
    $image->rtime = time();

    erLhcoreClassModelGalleryRated24::addRate($image->pid,$rate);
       
    erLhcoreClassGallery::getSession()->update($image);
    
    //Clear top rated listing cache
    CSCacheAPC::getMem()->increaseCacheVersion('top_rated');
    CSCacheAPC::getMem()->increaseCacheVersion('top_rated_'.$image->aid); //Album top rated version
    
    // Clear last rated cache version
    CSCacheAPC::getMem()->increaseCacheVersion('last_rated');    
    CSCacheAPC::getMem()->increaseCacheVersion('last_rated_'.$image->aid);
    
    //We expire only custom subshards based on sort mode
    erLhcoreClassGallery::expireShardIndexByIdentifier(array('album_id_'.$image->aid),array('pic_rating DESC, votes DESC, pid DESC','pic_rating ASC, votes ASC, pid ASC','rtime DESC, pid DESC','rtime ASC, pid ASC'));
    
    // Clear rated recetly images cache
    CSCacheAPC::getMem()->increaseCacheVersion('ratedrecent_version');
    
    echo json_encode(array('result' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/addvote','Thank you for your vote'),'error' => 'false'));
    exit;      
}

?>