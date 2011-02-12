<?php


    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lastuploadstoalbumsadmin.tpl.php');    
        
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true));
    $pages->setItemsPerPage(20);
    $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbumsadmin');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
        
    $Result['content'] = $tpl->fetch();
    
    $path = array();
    $Result['path'] = array(array('title' => 'Last uploads to albums','url' => erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbumsadmin')));
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/category','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }    


?>