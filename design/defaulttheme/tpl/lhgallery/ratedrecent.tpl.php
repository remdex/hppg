<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratedrecent','Top rated images in 24 hours.');?></h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?   
  	   $appendImageMode = '/(mode)/ratedrecent';
       $items = erLhcoreClassModelGalleryRated24::getImages(array('disable_sql_cache' => true,'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list_popularrecent.tpl.php'));?> 

     
<? } else { ?>
<p>There are no images.</p>
<? } ?>

