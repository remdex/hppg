<?php

// Simple is it? :)
$image = $Params['user_object'];

// We can rotate only image type objects
if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE && $Params['user_parameters']['action'] == 'rotate') {   
     
    $photoPath = 'albums/'.$image->filepath;
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'rotate_original', $photoPath.$image->filename, $photoPath.$image->filename );
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoPath.$image->filename, $photoPath.'/normal_'.$image->filename ); 
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoPath.$image->filename, $photoPath.'/thumb_'.$image->filename ); 
    
    $config = erConfigClassLhConfig::getInstance();
        
    chmod($photoPath.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    chmod($photoPath.'/normal_'.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    chmod($photoPath.'/thumb_'.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));

    $imageAnalyze = new ezcImageAnalyzer( $photoPath.$image->filename ); 	       
    $image->pwidth = $imageAnalyze->data->width;
    $image->pheight = $imageAnalyze->data->height;
    $image->clearCache();
    erLhcoreClassGallery::getSession()->update($image);
    
    echo json_encode(array('error' => 'false','time' => time()));
} elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE && $Params['user_parameters']['action'] == 'switch') {   
     
    $photoPath = 'albums/'.$image->filepath;
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'switch_original', $photoPath.$image->filename, $photoPath.$image->filename );
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoPath.$image->filename, $photoPath.'/normal_'.$image->filename ); 
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoPath.$image->filename, $photoPath.'/thumb_'.$image->filename ); 
    
    $config = erConfigClassLhConfig::getInstance();
        
    chmod($photoPath.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    chmod($photoPath.'/normal_'.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    chmod($photoPath.'/thumb_'.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));

    $imageAnalyze = new ezcImageAnalyzer( $photoPath.$image->filename ); 	       
    $image->pwidth = $imageAnalyze->data->width;
    $image->pheight = $imageAnalyze->data->height;
    $image->clearCache();
    erLhcoreClassGallery::getSession()->update($image);
    
    echo json_encode(array('error' => 'false','time' => time()));
} elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE && $Params['user_parameters']['action'] == 'switchv') {   
     
    $photoPath = 'albums/'.$image->filepath;
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'switchv_original', $photoPath.$image->filename, $photoPath.$image->filename );
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoPath.$image->filename, $photoPath.'/normal_'.$image->filename ); 
    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoPath.$image->filename, $photoPath.'/thumb_'.$image->filename ); 
    
    $config = erConfigClassLhConfig::getInstance();
        
    chmod($photoPath.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    chmod($photoPath.'/normal_'.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    chmod($photoPath.'/thumb_'.$image->filename,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));

    $imageAnalyze = new ezcImageAnalyzer( $photoPath.$image->filename ); 	       
    $image->pwidth = $imageAnalyze->data->width;
    $image->pheight = $imageAnalyze->data->height;
    $image->clearCache();
    erLhcoreClassGallery::getSession()->update($image);
    
    echo json_encode(array('error' => 'false','time' => time()));
} else {
    echo json_encode(array('error' => 'true'));
}


exit;