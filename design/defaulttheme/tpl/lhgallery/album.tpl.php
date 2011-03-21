<div class="header-list">
<?php
$urlAppendSort = '';
$urlSortBase  = $album->url_path;
?>
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/order_box.tpl.php'));?>
<h1><?=htmlspecialchars($album->title)?></h1>
</div>

<?php if ($album->description != '') : ?>
<div class="album-description">
<a class="expand-icon" onclick="hw.explandAlbumInfo($(this))" rel="Less &raquo;">More &raquo;</a>
<div class="album-desc">
<?=erLhcoreClassGallery::make_clickable(nl2br(htmlspecialchars($album->description)))?>
</div>
</div>
<?php endif;?>

<? if ($pages->items_total > 0) { ?>         
  <?  
      $items = erLhcoreClassModelGalleryImage::getImages(array('filter_shard' => $filter_shard,'use_index' => $use_index,'smart_select' => true,'sort' => $modeSQL,'disable_sql_cache' => true,'filter' => array('aid' => $album->aid)+$filterArray,'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>      
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?>           
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.</p>

<? } ?>

