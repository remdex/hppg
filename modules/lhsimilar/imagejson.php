<?php

$cacheKeyImageView = md5('image_similar_window_json_'.(int)$Params['user_parameters']['image_id'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
$cache = CSCacheAPC::getMem(); 

if (($Result = $cache->restore($cacheKeyImageView)) === false)
{
    if ((int)$Params['user_parameters']['image_id'] > 0){
        try {
            $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
        } catch (Exception $e) {
            $Image = false;
        }
    } else {
        $Image = false;
    }
    
    if ($Image !== false)
    {
        $tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/uploadsimilar.tpl.php');
        $xmlRPCClient = erLhcoreClassModelGalleryImgSeekData::getImgSeekClientInstance();
        
        try {        
            $similarImages = $xmlRPCClient->execute( array (
                'op'   => erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'query_img_function' ),
                'dbid' => erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),
                'id'   => $Image->pid,
                'nr'   => 24
            ));
            
            if ( !empty($similarImages->result) ) {
                $similarID = array();
                foreach ($similarImages->result as $imageSimilarData)
                {
                    $similarID[$imageSimilarData->id] = null;
                }
                
                $similarImagesObjects = erLhcoreClassModelGalleryImage::getImages(array('filterin' => array('pid' => array_keys($similarID))));
                
                foreach ($similarID as $id => & $data)
                {
                    if (isset($similarImagesObjects[$id])) {
                        $data = $similarImagesObjects[$id];
                    }
                }
                
                $tpl->set('items',array_filter($similarID));
            } else {
                $tpl->set('items',array());
            }
            
        } catch (Exception $e){
            $similarImages = array();
            $tpl->set('items',array());
        }
        $Result = array('result' => $tpl->fetch());
    }
    
    $cache->store($cacheKeyImageView,$Result);
}

echo json_encode($Result);
exit;