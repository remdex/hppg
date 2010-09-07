<div class="header-list">
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/resolution_box.tpl.php'));?>

<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popular','Most popular images')?></h1>
</div>

<?php if ($pages->items_total > 0) { ?>
         
<?php 
  $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'filter' => $filterArray,'sort' => 'hits DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
?>   
   
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
          
<?php } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popular','No records.')?></p>

<?php } ?>

