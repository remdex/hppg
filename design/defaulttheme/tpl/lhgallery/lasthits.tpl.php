<div class="header-list">
<h1>Last viewed images</h1>
</div>
<? if ($pages->items_total > 0) { ?>
         
  <? 
    // Last hits disable query cache. Like view cache expires regullary. No need there.
    $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'sort' => 'mtime DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
    $appendImageMode = '/(mode)/lasthits';
  ?>   
   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
          
<? } else { ?>

<p>Nėra įrašų.</p>

<? } ?>

