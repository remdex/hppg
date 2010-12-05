<div class="header-list">

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/resolution_box.tpl.php'));?>

<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','Last commented images')?></h1>
</div>
<? if ($pages->items_total > 0) { ?>
         
<? 
    $items = erLhcoreClassModelGalleryImage::getImages(array('filter_shard' => $filter_shard,'smart_select' => true,'disable_sql_cache' => true,'filter' => $filterArray,'sort' => 'comtime DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
?>   
   
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
   
          
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastcommented','No records.')?></p>

<? } ?>

