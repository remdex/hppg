<?php

$imgData = str_replace('data:image/jpeg;base64,','',$_POST['img']);
$filePath = 'var/tmpfiles/'.sha1($imgData).'.jpg';
file_put_contents($filePath,base64_decode($imgData));

if (($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($filePath)) !== false)
{
    $xmlRPCClient = erLhcoreClassModelGalleryImgSeekData::getImgSeekClientInstance();
    $filePath = erLhcoreClassSystem::instance()->SiteDir . $filePath;
    
    $idSimilar = erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'image_random_start') + rand(1,1000);
    
    $xmlRPCClient->removeImg(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar);
    $xmlRPCClient->addImg(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar,$filePath);

    try {
        $similarImages = $xmlRPCClient->queryImgID(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar,25);
    } catch (Exception $e){
        $similarImages = array();
    }
    
     if (!empty($similarImages)) {
        $similarID = array();
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

    $tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/uploadsimilar.tpl.php');
    $tpl->set('items',array_filter($similarID));
    $xmlRPCClient->removeImg(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$idSimilar);

    unlink($filePath);

    echo json_encode(array('success' => 'true','result' => $tpl->fetch()));
}

exit;
?>