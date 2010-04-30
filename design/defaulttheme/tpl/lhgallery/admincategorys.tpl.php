<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Category');?> - <?= $category !== false ? htmlspecialchars($category->name) : erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Home')?></h1>
</div>
<?php
$categoryID = $category !== false ? $category->cid : 0;
?>
<a href="<?=erLhcoreClassDesign::baseurl('/gallery/createcategory/')?><?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Create a category');?></a>

<? 

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('parent' => $categoryID)));
$pages->translationContext = 'gallery/admincategorys';
$pages->default_ipp = 8;
$pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$categoryID;
$pages->paginate();
if ($pages->items_total > 0) :?>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Title');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>

<?php if (isset($pages)) : ?> 
    <div class="navigator"><?=$pages->display_pages();?> <div class="right"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Page %currentpage of %totalpage',array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Found')?> - <?=$pages->items_total?></div></div>
<? endif;

foreach (erLhcoreClassModelGalleryCategory::getParentCategories($category !== false ? $category->cid : 0,$pages->items_per_page, $pages->low) as $categoryItem) : ?>
    <tr>
        <td width="1%"><?=$categoryItem->cid?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys/')?><?=$categoryItem->cid?>"><?=htmlspecialchars($categoryItem->name)?></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/editcategory/')?><?=$categoryItem->cid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Edit category');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Edit category');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/deletecategory/')?><?=$categoryItem->cid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Delete category');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Delete category');?>" /></a></td>
    </tr>
<? endforeach; ?>

</table><br />
<? endif;?>
<br />
<? 

if ($category !== false) :  ?>
<div class="header-list">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Category albums');?> <?= $category !== false ? ' - '.htmlspecialchars($category->name) : ' - '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Home')?></h3>
</div>
<?

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('filter' => array('category' => $category->cid)));
$pages->translationContext = 'gallery/admincategorys';
$pages->default_ipp = 8;
$pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/managealbum/').$category->cid;
$pages->paginate();

if ($pages->items_total > 0) :                   
                     
$items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
?>   
   
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_admin.tpl.php')); ?>

<?php else: ?>

<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','This category does not have an album.');?>

<?php endif;

endif;
?> 
<? if ($category !== false) : ?>
<a href="<?=erLhcoreClassDesign::baseurl('/gallery/createalbumadmin/')?><?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Create an album');?></a>
<?endif;?>