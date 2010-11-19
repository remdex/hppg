<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastsearches','Last searches')?></h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?  
      $items = erLhcoreClassModelGallerySearchHistory::getSearches(array('offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>      

<div class="float-break">
<ul>
	<?php foreach ($items as $search) : ?>									
	   <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/<?=urlencode($search->keyword);?>">&raquo; <strong><?=htmlspecialchars($search->keyword);?></strong>, found - <?=$search->countresult;?>, executed times - <?=$search->searches_done?>, search execution time - <?=date('Y-m-d H:i:s',$search->last_search);?></a></li>
	<?endforeach;?>
</ul>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>

</div>
   
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastsearches','No records')?></p>

<? } ?>