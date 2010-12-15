<?php if ($enter_keyword === true) : ?>
<div class="header-list">
<h1>Please enter keyword or phrase</h1>
</div>

<form action="<?=erLhcoreClassDesign::baseurl('gallery/search')?>">
<input type="text" class="inputfield" autocomplete="off" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Enter keyword or phrase')?>" class="keywordField" name="SearchText" value="" >
<input type="submit" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search entire gallery')?>" class="default-button" name="doSearch" value="Search"> enter keyword or phrase
</form>

<?php else : ?>

<div class="header-list">

<?php 
$urlAppendSort = '/(keyword)/'.urlencode($keyword);
$urlSortBase  = erLhcoreClassDesign::baseurl('gallery/search');
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

<?php endif;?>