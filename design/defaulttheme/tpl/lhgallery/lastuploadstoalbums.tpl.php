<div class="header-list">
<h1>Last uploads to albums</h1>
</div>

<? if ($pages->items_total > 0) { ?>         
  <? 
      $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('hidden' => 0),'sort' => 'addtime DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   

  <?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));?> 
           
<? } else { ?>

No records.

<? } ?>





