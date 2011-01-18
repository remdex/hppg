<?php

try {
        $image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
         
        if ($image->votes > 0){
            $image->votes = $image->votes - 1;
            $image->rtime = 0;
            if ($image->votes == 0) {
                $image->pic_rating = 0;
                erLhcoreClassModelGalleryRated24::deleteByPid($image->pid);
            }
        }
        
        erLhcoreClassGallery::getSession()->update($image);
        
        //Clear top rated listing cache
        CSCacheAPC::getMem()->increaseCacheVersion('top_rated');
        CSCacheAPC::getMem()->increaseCacheVersion('top_rated_'.$image->aid); //Album top rated version
        
        // Clear last rated cache version
        CSCacheAPC::getMem()->increaseCacheVersion('last_rated');    
        CSCacheAPC::getMem()->increaseCacheVersion('last_rated_'.$image->aid);
        
        //We expire only custom subshards based on sort mode
        erLhcoreClassGallery::expireShardIndexByIdentifier(array('album_id_'.$image->aid),array('pic_rating DESC, votes DESC, pid DESC','pic_rating ASC, votes ASC, pid ASC','rtime DESC, pid DESC','rtime ASC, pid ASC'));
            
        // Update rating attributes
        erLhcoreClassModelGallerySphinxSearch::indexAttributes($image,array('pic_rating' => 'pic_rating','votes' => 'votes','rtime' => 'rtime'));
    
        // Clear rated recetly images cache
        CSCacheAPC::getMem()->increaseCacheVersion('ratedrecent_version');
        
        echo json_encode(array('result' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/addvote','Vote deducted!'),'error' => 'false'));
        exit;
    
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
    }