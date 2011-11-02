<?php

$uploader = new qqFileUploader(explode(',',str_replace('\'','',erLhcoreClassModelSystemConfig::fetch('allowed_file_types')->current_value)), erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024);
$result = $uploader->handleUpload('var/tmpfiles/');

if (isset($result['success']) && $result['success'] == 'true' && ($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($uploader->getFilePath())) !== false)
{
    $result['filepath'] = $uploader->getFilePath();  
    $result['filename'] = $uploader->getFileName();
    $result['filename_user'] = $uploader->getUserFileName();
        
    $fileNameGenerated = sha1(microtime().time().$result['filename']).'jpg';
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $result['filepath'],'var/tmpfiles/'.$fileNameGenerated );
        
    $xmlRPCClient = erLhcoreClassModelGalleryImgSeekData::getImgSeekClientInstance();
    $filePath = erLhcoreClassSystem::instance()->SiteDir . 'var/tmpfiles/'.$fileNameGenerated;
    
    $idSimilar = erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'image_random_start') + rand(1,1000);
    
    $xmlRPCClient->removeImg(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar);
    $xmlRPCClient->addImg(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar,$filePath);
 
    $similarImages = $xmlRPCClient->queryImgID(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar,25);

    $similarID = array();    
    if (!empty($similarImages)){
        foreach ($similarImages as $imageSimilarData)
        {
            $similarID[$imageSimilarData[0]] = null;
        }
    
        $similarImagesObjects = erLhcoreClassModelGalleryImage::getImages(array('filterin' => array('pid' => array_keys($similarID))));   
        
        foreach ($similarID as $id => & $data)
        {
            $data = $similarImagesObjects[$id];
        }
    }
    
    unlink($result['filepath']);
    unlink('var/tmpfiles/'.$fileNameGenerated);
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/uploadsimilar.tpl.php');
    $tpl->set('items',array_filter($similarID));
    $xmlRPCClient->removeImg(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar);
    
    echo json_encode(array('success' => 'true','result' => $tpl->fetch()));    
    
}

exit;