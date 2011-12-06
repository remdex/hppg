<?php

$imgData = str_replace('data:image/jpeg;base64,','',$_POST['img']);
$filePath = 'var/tmpfiles/'.sha1($imgData).'.jpg';
file_put_contents($filePath,base64_decode($imgData));

if (($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($filePath)) !== false)
{
    $xmlRPCClient = erLhcoreClassModelGalleryImgSeekData::getImgSeekClientInstance();
    $filePath = erLhcoreClassSystem::instance()->SiteDir . $filePath;
    
    try {
        $similarImagesResult = $xmlRPCClient->execute(array(
            'op'   => erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'query_img_sketch_function'),
            'dbid' => erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'database_id' ),
            'nr'   => 25,
            'sk'   => 1,
            'fp'   => $filePath
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
    } catch (Exception $e){
        $similarID = array();
    }
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/uploadsimilar.tpl.php');
    $tpl->set('items',array_filter($similarID));
    
    unlink($filePath);

    echo json_encode(array('success' => 'true','result' => $tpl->fetch()));
}

exit;
?>