<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popularrecect','Most popular images in 24 hours.');?></h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?   
  	   $appendImageMode = '/(mode)/popularrecent';	           
       $items = erLhcoreClassModelGalleryPopular24::getImages(array('disable_sql_cache' => true,'sort' => 'hits DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list_popularrecent.tpl.php'));?> 

     
<? } else { ?>
<p>There are no images.</p>
<? } ?>

