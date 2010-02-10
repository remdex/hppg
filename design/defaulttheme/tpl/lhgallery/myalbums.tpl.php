<div class="header-list">
<h1>My albums</h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?             
       $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('owner_id' => $owner_id),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_albums_list.tpl.php'));?> 
    
  <p><a href="<?=erLhcoreClassDesign::baseurl('/gallery/createalbum/')?>">Create a new album.</a></p>
          
<? } else { ?>

<p>You do not have any album. <a href="<?=erLhcoreClassDesign::baseurl('/gallery/createalbum/')?>">Create an album.</a></p>

<? } ?>

