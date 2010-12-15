<?php

$pallete_id = (int)$Params['user_parameters']['pallete_id'];


$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/color.tpl.php');
$tpl->set('show_pallete',true);

if ($pallete_id > 0) {    
    try {
        $pallete = erLhcoreClassModelGalleryPallete::fetch($pallete_id);        
        $tpl->set('show_pallete',false);    
        $pages = new lhPaginator();
        $pages->items_total = erLhcoreClassModelGalleryPallete::getListCountPalleteImages(array('filter' => array('pallete_id' => $pallete_id)));
        $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/color').'/'.$pallete_id;
        $pages->paginate();
        $tpl->set('pages',$pages);
        $tpl->set('appendImageMode','/(mode)/color/(color)/'.$pallete_id);
        $tpl->set('pallete_id',$pallete_id);
        $tpl->set('pallete',$pallete);
        $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('gallery/color').'/'.$pallete_id);        
    } catch (Exception $e) {
        
    }
}


$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Images by color')));    

if ($Params['user_parameters_unordered']['page'] > 1) {        
    $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
}
    
