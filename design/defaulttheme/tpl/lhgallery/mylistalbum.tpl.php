<div class="header-list">
       <div class="right">
               <a href="<?=erLhcoreClassDesign::baseurl('gallery/addimages')?>/<?=$album->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Add images');?>" /></a>    
               <a href="<?=erLhcoreClassDesign::baseurl('gallery/albumedit')?>/<?=$album->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Edit album');?>" /></a>
               <a href="<?=erLhcoreClassDesign::baseurl('gallery/deletealbum')?>/<?=$album->aid?>" onclick="return hw.confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Are you sure?')?>')"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Delete album');?>" /></a>
       </div>
       
<h1><?=htmlspecialchars($album->title)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>
         
  <? 
            $items = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'albumlist_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$album->aid),'filter' => array('aid' => $album->aid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_image_list.tpl.php'));?> 
          
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/mylistalbum','No records.')?></p>

<? } ?>

