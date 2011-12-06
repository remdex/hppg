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
        $imgPath = array_pop($path);
        $imgPath['url'] = $Image->url_path;        
        $path[] = $imgPath;
        $path[] = array('url' => erLhcoreClassDesign::baseurl('similar/image') . '/' . $Image->pid, 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('similar/image','Similar images to'));
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('similar/image') . '/' . $Image->pid;
        
        if ($Image->caption != '') {
            $Result['description_prepend'] = erTranslationClassLhTranslation::getInstance()->getTranslation('similar/image','Similar images to').' - '.erLhcoreClassBBCode::make_plain(htmlspecialchars($Image->caption));
        }

    } else {
        $path = array(array('url' => erLhcoreClassDesign::baseurl('similar/image'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('similar/image','Search for visualy similar images')));
        $tpl->set('image',false);
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('similar/image');
        $Result['description_prepend'] = erTranslationClassLhTranslation::getInstance()->getTranslation('similar/image','Search for visualy similar images');
    }
    
    $Result['content'] = $tpl->fetch();
    $Result['path'] = $path;
    $Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/fileuploader.js').'"></script>';
    
    $cache->store($cacheKeyImageView,$Result);
}