<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?>
<div class="left-infobox search-infobox">				
	<h3><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastsearches')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last searches')?></a></h3>
	<ul>
<?php 
$cache = CSCacheAPC::getMem();
if (($resultLastSearchTplBlock = $cache->restore('last_search_tpl_block')) === false) :
ob_start();
?>

	<?php foreach (erLhcoreClassModelGalleryLastSearch::getSearches() as $search) : ?>									
	   <li>
	   <a class="cnt" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">(<?=$search->countresult;?>)</a>	   
	   <a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>"><?=htmlspecialchars($search->keyword);?></a>					  
	<?endforeach;?>

<?php 
$resultLastSearchTplBlock = ob_get_clean();
$cache->store( 'last_search_tpl_block', $resultLastSearchTplBlock, 60 ); //Cache for 60 seconds
endif;
echo $resultLastSearchTplBlock; ?>
	</ul>
</div>
<?php
endif; ?>