<div class="header-list">

<?php 
$urlAppendSort = '/(keyword)/'.urlencode($keyword);
$urlSortBase  = erLhcoreClassDesign::baseurl('/gallery/search');
$enableRelevance = true;
?> 

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/order_box.tpl.php'));?>

<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Search results')?> - <?=htmlspecialchars($keyword)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>

  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
  
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Nothing found')?>...</p>

<? } ?>

