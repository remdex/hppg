<div class="header-list">
<h1>Category - <?= $category !== false ? htmlspecialchars($category->name) : 'Home'?></h1>
</div>

<? if ($pages->items_total > 0) { ?>         
  <? 
       $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_admin.tpl.php'));?> 
          
<? } else { ?>

<p>Nėra įrašų.</p>

<? } ?>
