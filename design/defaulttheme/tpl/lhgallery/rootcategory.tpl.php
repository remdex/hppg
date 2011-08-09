<?php 
$cache = CSCacheAPC::getMem();
foreach (erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => 0),'cache_key' => 'version_'.$cache->getCacheVersion('category_0'))) as $category) : ?>
<div class="category">
<div class="header-list"><h1><a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a></h1></div>
<? if ($category->description != '') : ?>
<p><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($category->description))?></p>
<?endif;?>
<? 
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true,'filter' => array('category' => $category->cid)));
$pages->setItemsPerPage(8);
$pages->serverURL = $category->path_url;
$pages->paginate();    

if ($pages->items_total > 0) :

$items=erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
?>  

<?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php')); ?>

<?php endif;?>    
    
<?php if ($category->hide_frontpage != 1) :

$subcategorys = erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => $category->cid),'cache_key' => 'version_'.$cache->getCacheVersion('category_'.$category->cid)));
if (count($subcategorys) > 0) : ?>
 <?php include(erLhcoreClassDesign::designtpl('lhgallery/subcategory_list.tpl.php'));?> 
<?endif;


endif;?>

</div>
<?php endforeach;?>