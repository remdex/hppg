<div class="header-list">

<?php 
$urlAppendSort = '';
$urlSortBase  = $album->url_path;
?>
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/order_box.tpl.php'));?>

<h1><?=htmlspecialchars($album->title)?></h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?  
      $items = erLhcoreClassModelGalleryImage::getImages(array('sort' => $modeSQL,'cache_key' => 'albumlist_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$album->aid),'filter' => array('aid' => $album->aid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>      
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?>           
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.</p>

<? } ?>

