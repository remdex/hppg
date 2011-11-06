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
    try {
        $similarImages = $xmlRPCClient->execute( array (
            'op'   => 'queryimgid',
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