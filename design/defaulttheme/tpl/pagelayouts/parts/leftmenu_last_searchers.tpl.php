<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?>
	  		 <div class="left-infobox search-infobox">				
					<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last searches')?></h3>
					<ul>
					<?php foreach (erLhcoreClassModelGalleryLastSearch::getSearches() as $search) : ?>									
					   <li>
					   <a class="cnt" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">(<?=$search->countresult;?>)</a>	   
					   <a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>"><?=htmlspecialchars($search->keyword);?></a>
					   </li>
					<?endforeach;?>
					</ul>									
             </div>
<?php endif; ?>