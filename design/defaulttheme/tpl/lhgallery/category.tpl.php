<div class="header-list">
<h1><a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a></h1>
</div>

<?php 
$skipAlbumListJS = true;
if ($category->description != '') : ?>
<div class="album-description">
<a class="expand-icon" onclick="hw.explandAlbumInfo($(this))" rel="Less &raquo;">More &raquo;</a>
<div class="album-desc">
<?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($category->description))?>
</div>
</div>
<?php endif;?>


<? if ($pagesCurrent->items_total > 0) { ?>         
  <? 
      $pages = $pagesCurrent;
      $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid,'hidden' => 0),'offset' => $pagesCurrent->low, 'limit' => $pagesCurrent->items_per_page));
  ?>   
   
  <?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));?> 
           
<? } else { ?>

<? } ?>

<? 

$cache = CSCacheAPC::getMem();

$pagesSubcategorys = new lhPaginator();
$pagesSubcategorys->items_total = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('parent' => $category->cid) ,'cache_key' => 'version_'.$cache->getCacheVersion('category_'.$category->cid)));
$pagesSubcategorys->setItemsPerPage(8);
$pagesSubcategorys->serverURL = $category->path_url;
$pagesSubcategorys->paginate();
     
if ($pagesSubcategorys->items_total > 0) : 
$subcategorys = erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => $category->cid),'cache_key' => 'version_'.$cache->getCacheVersion('category_'.$category->cid),'offset' => $pagesSubcategorys->low, 'limit' => $pagesSubcategorys->items_per_page));
?>

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/subcategory_list_full.tpl.php'));?> 

<?php $pages = $pagesSubcategorys; ?>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>

<?endif;?>

<script>
var _lactq = _lactq || [];
_lactq.push({'f':'hw_init_info_window_album','a':[]});
</script>

