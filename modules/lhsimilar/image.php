<?php

if ((int)$Params['user_parameters']['image_id'] > 0){
    try {
        $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
    } catch (Exception $e) {
        $Image = false;
    }
} else {
    $Image = false;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/similarimage.tpl.php');

if ($Image !== false)
{   
    $xmlRPCClient = erLhcoreClassModelGalleryImgSeekData::getImgSeekClientInstance();
    try{
        $similarImages = $xmlRPCClient->queryImgID(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'database_id' ),$Image->pid,24);
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
        $tpl->set('items',array_filter($similarID));
    }
    $tpl->set('image',$Image);
    $path = $Image->path;
    $path[] = array('title' => 'Similar images to');
} else {
    $path = array(array('title' => 'Search for visualy similar images'));
    $tpl->set('image',false);
}

$Result['content'] = $tpl->fetch();
$Result['path'] = $path;
$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/fileuploader.js').'"></script>';