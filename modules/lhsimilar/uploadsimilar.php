<?php

$uploader = new qqFileUploader(explode(',',str_replace('\'','',erLhcoreClassModelSystemConfig::fetch('allowed_file_types')->current_value)), erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024);
$result = $uploader->handleUpload('var/tmpfiles/');

if (isset($result['success']) && $result['success'] == 'true' && ($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($uploader->getFilePath())) !== false)
{
    $result['filepath'] = $uploader->getFilePath();  
    $result['filename'] = $uploader->getFileName();
    $result['filename_user'] = $uploader->getUserFileName();
        
    $xmlRPCClient = erLhcoreClassModelGalleryImgSeekData::getImgSeekClientInstance();
    
    $similarImagesResult = $xmlRPCClient->execute(array(
        'op'   => erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'query_img_sketch_function'),
        'dbid' => erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'database_id' ),
        'nr'   => 25,
        'sk'   => 0,
        'fp'   => erLhcoreClassSystem::instance()->SiteDir . $result['filepath']
    ));
    
    $similarImages = $similarImagesResult->result;
    $similarID = array();    
    if (!empty($similarImages)){
        foreach ($similarImages as $imageSimilarData)
        {
            $similarID[$imageSimilarData->id] = null;
        }
    
        $similarImagesObjects = erLhcoreClassModelGalleryImage::getImages(array('filterin' => array('pid' => array_keys($similarID))));   
        
        foreach ($similarID as $id => & $data)
        {
            if (isset($similarImagesObjects[$id])){
                $data = $similarImagesObjects[$id];
            }
        }
    }
    
    unlink($result['filepath']);
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/uploadsimilar.tpl.php');
    $tpl->set('items',array_filter($similarID));
    
    echo json_encode(array('success' => 'true','result' => $tpl->fetch()));    
    
}

exit;