<div id="footer">
	<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'redirect_mobile' ) != false) : ?>
	&nbsp;<a href="<?=erLhcoreClassDesign::baseurldirect('/m')?>">Browse mobile version</a>
	<?php endif;?>
	
    <div class="right"><abbr title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','It\'s NOT fake!')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Rendered in')?>: <?=number_format(set_time($GLOBALS['star_microtile'], microtime()), 5);?> s.</abbr>, powered by <a href="http://hppgallery.com" title="High performance photo gallery">HPPG</a>, Design <a href="http://pauliusc.lt">http://pauliusc.lt</a></div>
	<div class="creator copyright">
	<?php
	$cache = CSCacheAPC::getMem();
	$cacheVersion = $cache->getCacheVersion('article_cache_version');
	if (($ResultCache = $cache->restore(md5($cacheVersion.'_footer_article'.erLhcoreClassSystem::instance()->SiteAccess))) === false)
    {
    	$value = (int)erLhcoreClassModelSystemConfig::fetch('footer_article_id')->current_value;
    	if ($value > 0)
    	{
    		try {
	    		$article = erLhcoreClassModelArticleStatic::fetch($value);        				
	    		$cache->store(md5($cacheVersion.'_footer_article'.erLhcoreClassSystem::instance()->SiteAccess),$article->content_front);    		
	    		echo $article->content_front;
    		} catch (Exception $e) {
    			// Do nothing
    		}
    	}
    } else {
    	echo $ResultCache;
    }
	?>	
	</div>
</div>

<?php 
if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true) {
	$debug = ezcDebug::getInstance(); 
	echo $debug->generateOutput(); 
}
?>