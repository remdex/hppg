<h1>News</h1>
<ol>
    <li>2010-01-29 - sorting in search results.</li>
    <li>2010-01-29 - now you can tag images. It will help make search even more content related</li>
    <li>2010-01-25 - more features. Sorting options in albums. All of this with blazing speed.</li>
    <li>2010-01-16 - completely changed gallery engine. Coppermine gallery done it's beutifull job. But now it's time to create a better one. Whole gallery was programmed only in 7 days :) not counting working hours :).<br>
    </li>
    <li>2009-04-20 - from now if you enter 1280x1024 for example in search input you will see all images this resolution. You can also append any keyword you want.<br>
    </li>
    <li>2009-04-16 - finished imported around 19GB of images :) enjoy.</li>
    <li>2009-03-30 - added a little bit images. It's a small part of new part witch is waiting to be added :) Around 19GB of images :D</li>
    <li>2008-12-17 - no ugly popups anymore. Fullsize images are loaded directly in page :)</li>
    <li>2008-12-15 - Some pagination bug fixes also finished upload all images :) Enjoy :)<br>    </li>
    <li>2008-12-14 - back to normal, added about 35% of new images, more comming :)<br>
    </li>
    <li>2008-12-09 - sorry for slow site, it will be back to normal after few days. Site will contain much more images...</li>
    <li>2008-11-01 - Site opened. Site is dedicated for hentai/anime adult wallpapers</li>
    <li>Wanna advertise write me :)</li>
    <li><font face="Tahoma" size="2"><font face="Tahoma" size="2">Have questions ? write me</font><font face="Tahoma" size="2"> remdex{eta}gmail.com</font></font>     </li>

</ol>

<div id="front-banner">
</div>

<iframe src="/ads/ads.php?id=35345" framespacing="0" frameborder="no" scrolling="no" width="720" height="150" allowtransparency="true"></iframe>

<?php foreach (erLhcoreClassModelGalleryCategory::getParentCategories() as $category) : ?>
<div class="category">
<div class="header-list"><h1><a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a></h1></div>
<? if ($category->description != '') : ?>
<p><?=$category->description?></p>
<?endif;?>
<? 
$noBottom = true;
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true,'filter' => array('category' => $category->cid)));
$pages->translationContext = 'gallery/album';
$pages->default_ipp = 8;
$pages->serverURL = $category->path_url;
$pages->paginate();    

if ($pages->items_total > 0) :

$items=erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
$noAds = true;
?>  

<?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));

endif;?>    
    
<?php if ($category->hide_frontpage != 1) :
$subcategorys = erLhcoreClassModelGalleryCategory::getParentCategories($category->cid);
if (count($subcategorys) > 0) : ?>
 <?php include(erLhcoreClassDesign::designtpl('lhgallery/subcategory_list.tpl.php'));?> 
<?endif;
endif;?>

</div>
<?php endforeach;?>