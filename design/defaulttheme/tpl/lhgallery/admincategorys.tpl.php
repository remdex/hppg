<div class="header-list">
<h1>Category - <?= $category !== false ? $category->name : 'Home'?></h1>
</div>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Title');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelGalleryCategory::getParentCategories($category !== false ? $category->cid : 0) as $category) : ?>
    <tr>
        <td width="1%"><?=$category->cid?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys/')?><?=$category->cid?>"><?=htmlspecialchars($category->name)?></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/editcategory/')?><?=$category->cid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit group');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit group');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/deletecategory/')?><?=$category->cid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete group');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete group');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table><br />
<br />
<div class="header-list">
<h3>Albums category <?= $category !== false ? ' - '.htmlspecialchars($category->name) : ' - Home'?></h3>
</div>

<? 

if ($category !== false) :  

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('filter' => array('category' => $category->cid)));
$pages->translationContext = 'gallery/managealbum';
$pages->default_ipp = 8;
$pages->serverURL = '/gallery/managealbum/'.$category->cid;
$pages->paginate();

if ($pages->items_total > 0) :                   
                     
$items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
?>   
   
<?php 
include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_admin.tpl.php'));

endif;

endif;

?> 