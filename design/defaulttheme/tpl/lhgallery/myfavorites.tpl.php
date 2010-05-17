<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','My favorite images');?></h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?   
  	   $appendImageMode = '/(mode)/myfavorites';	           
       $items = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('disable_sql_cache' => true,'filter' => array('session_id' => $session->id),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list_favorites.tpl.php'));?> 

     
<? } else { ?>
<p>You do not have any image marked as favourite.</p>
<? } ?>

