<div class="header-list">
<h1>Most popular images</h1>
</div>
<? if ($pages->items_total > 0) { ?>
         
  <? 
            $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'sort' => 'hits DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
   
  <?php 
  $appendImageMode = '/(mode)/popular';
  include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
          
<? } else { ?>

<p>Nėra įrašų.</p>

<? } ?>

