<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myalbums','My albums')?></h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?             
       $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('owner_id' => $owner_id),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
  <form action="<?=erLhcoreClassDesign::baseurl('gallery/myalbums')?>" method="post">
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_albums_list.tpl.php'));?> 
    
  <p>
  <a class="button-link" href="<?=erLhcoreClassDesign::baseurl('gallery/createalbum')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myalbums','New album')?></a>&nbsp;<input type="submit" class="default-button" name="UpdatePriorityAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Update priority');?>"/>
  </p>
  </form>
  
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myalbums','You do not have any album.')?> <a href="<?=erLhcoreClassDesign::baseurl('gallery/createalbum')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myalbums','Create a new album.')?></a></p>

<? } ?>

