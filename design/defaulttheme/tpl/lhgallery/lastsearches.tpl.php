<div class="header-list">
<h1>Last searches</h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?  
      $items = erLhcoreClassModelGallerySearchHistory::getSearches(array('offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>      

<div class="float-break">
<ul>
	<?php foreach ($items as $search) : ?>									
	   <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">&raquo; <strong><?=htmlspecialchars($search->keyword);?></strong>, found - <?=$search->countresult;?>, executed times - <?=$search->searches_done?>, search execution time - <?=date('Y-m-d H:i:s',$search->last_search);?></a></li>
	<?endforeach;?>
</ul>

 <?php if (isset($pages)) : ?>
 <div class="nav-container">
    <div class="navigator">
    <div class="right found-total"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('rss/category',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, Found - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?></div>
 </div>   
<? endif;?>


</div>
   
<? } else { ?>

<p>No records.</p>

<? } ?>