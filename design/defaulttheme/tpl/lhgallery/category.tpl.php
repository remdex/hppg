<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/category','Category')?> - <a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a></h1>
</div>

<? $subcategorys = erLhcoreClassModelGalleryCategory::getParentCategories($category->cid);
if (count($subcategorys) > 0) : 

?>
 <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/subcategory_list_full.tpl.php'));?> 
<?endif;?>
<? if ($pagesCurrent->items_total > 0) { ?>         
  <? 
      $pages = $pagesCurrent;
      $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pagesCurrent->low, 'limit' => $pagesCurrent->items_per_page));
  ?>   
   
  <?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));?> 
          
<? } else { ?>



<? } ?>