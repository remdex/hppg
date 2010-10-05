<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/admincategorys.tpl.php');

$pathCategorys = array();
$path = array(); 
$path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Home')); 

if (is_numeric($Params['user_parameters']['category_id']) && $Params['user_parameters']['category_id'] > 0)
{
    $Category = erLhcoreClassModelGalleryCategory::fetch($Params['user_parameters']['category_id']);
    $tpl->set('category',$Category);
    $pathObjects = array();
    erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$Category->cid);  
         
    foreach ($pathObjects as $pathItem)
    {
       $pathCategorys[] = $pathItem->cid; 
       $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
    }
    
} else {
    
    $tpl->set('category',false);
     
}

$Result['content'] = $tpl->fetch();
$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;





  
?>