<div class="header-list">
<h1><a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a></h1>
</div>

<? if ($pagesCurrent->items_total > 0) { ?>         
  <? 
      $pages = $pagesCurrent;
      $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pagesCurrent->low, 'limit' => $pagesCurrent->items_per_page));
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


<div class="nav-container">
    <div class="navigator">
    <div class="right found-total"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator',"Page %currentpage of %totalpage",array('currentpage' => $pagesSubcategorys->current_page,'totalpage' => $pagesSubcategorys->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Found')?> - <?=$pagesSubcategorys->items_total?></div>
    <?=$pagesSubcategorys->display_pages();?></div>
</div>   


<?endif;?>



