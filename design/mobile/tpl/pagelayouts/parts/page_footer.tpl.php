<div id="footer">
	<div style="float:left">
	<a href="javascript:hw.useRegular()">Use regular site</a>
	</div>
    <div class="right"><acronym title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','It\'s NOT fake!')?>"><?=number_format(set_time($GLOBALS['star_microtile'], microtime()), 5);?> s.</acronym>, powered by <a href="http://redmine.remdex.info/projects/hppg" title="High performance photo gallery">HPPG</a></div>
	<div class="creator copyright">
	<?php
	$cache = CSCacheAPC::getMem();
	$cacheVersion = $cache->getCacheVersion('article_cache_version');
	if (($Result = $cache->restore(md5($cacheVersion.'_footer_article'.erLhcoreClassSystem::instance()->SiteAccess))) === false)
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
    	echo $Result;
    }
	?>	
	</div>
</div>

<?php 
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'debug_output' ) == true) {
	$debug = ezcDebug::getInstance(); 
	echo $debug->generateOutput(); 
}
?>