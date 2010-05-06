<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastuploads','Last uploaded images')?></h1>
</div>
<? if ($pages->items_total > 0) { ?>
         
<? 
    $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true, 'sort' => 'ctime DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
    $appendImageMode = '/(mode)/lastuploads';
?>   
   
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
          
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastuploads','No records.')?></p>

<? } ?>

