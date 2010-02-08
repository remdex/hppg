<div class="header-list">
<h1><?=htmlspecialchars($album->title)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>
         
  <? 
            $items = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'albumlist_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$album->aid),'filter' => array('aid' => $album->aid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_image_list.tpl.php'));?> 
          
<? } else { ?>

<p>Nėra įrašų.</p>

<? } ?>

