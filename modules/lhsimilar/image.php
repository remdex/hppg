<?php

$cacheKeyImageView = md5('image_similar_window_'.(int)$Params['user_parameters']['image_id'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
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
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/similarimage.tpl.php');
    
    if ($Image !== false)
    {                       
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
    
    $cache->store($cacheKeyImageView,$Result);
}