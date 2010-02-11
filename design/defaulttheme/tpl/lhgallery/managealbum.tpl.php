<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','Category');?> - <?= $category !== false ? htmlspecialchars($category->name) : erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/mylistalbum','Home')?></h1>
</div>

<? if ($pages->items_total > 0) { ?>         
  <? 
       $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_admin.tpl.php'));?> 
          
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','No records.')?></p>

<? } ?>
