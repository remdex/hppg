<div class="header-list">
<h1>Last searches</h1>
</div>
<? if ($pages->items_total > 0) { ?>         
  <?  
      $items = erLhcoreClassModelGallerySearchHistory::getSearches(array('offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>      

<?php if (isset($pages)) : ?> 
    <div class="navigator"><?if ($pages->num_pages > 1) : ?><?=$pages->display_pages();?><?php endif;?> <div class="right"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('rss/category',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, Found - <?=$pages->items_total?></div></div>
<? endif;?>
<div class="float-break">
<br />
<ul>
	<?php foreach ($items as $search) : ?>									
	   <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">&raquo; <strong><?=htmlspecialchars($search->keyword);?></strong>, found - <?=$search->countresult;?>, executed times - <?=$search->searches_done?>, search execution time - <?=date('Y-m-d H:i:s',$search->last_search);?></a></li>
	<?endforeach;?>
</ul>
<br />
<? if (!isset($noAds)) : ?>
<div class="c-left"><br />
<script type="text/javascript"><!--
google_ad_client = "pub-3487178404951359";
//728x90, created 11/24/07
google_ad_slot = "3627829768";
google_ad_width = 728;
google_ad_height = 90;
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<?endif;?>
<?php if (isset($pages) && $pages->num_pages > 1 && !isset($noBottom)) : ?>
    <div class="navigator" style="clear:left;"><?=$pages->display_pages();?></div>
<? endif;?> 
</div>
   
<? } else { ?>

<p>No records.</p>

<? } ?>