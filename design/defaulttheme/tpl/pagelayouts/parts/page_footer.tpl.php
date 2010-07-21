<div id="footer">
	<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'redirect_mobile' ) != false) : 
	$instance = erLhcoreClassSystem::instance();  
	?>
	<a href="<?=$instance->WWWDir . $instance->IndexFile .  '/m'  . '/'?>">Browse mobile version</a>
	<?php endif;;?>
	
    <div class="right"><acronym title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','It\'s NOT fake!')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Rendered in')?>: <?=number_format(set_time($GLOBALS['star_microtile'], microtime()), 5);?> s.</acronym>, powered by <a href="http://code.google.com/p/hppg/" title="High performance photo gallery">HPPG</a></div>
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