<div id="footer">
    <div class="right"><acronym title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','It\'s NOT fake!')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Rendered in')?>: <?=number_format(set_time($GLOBALS['star_microtile'], microtime()), 5);?> s.</acronym>, powered by <a href="http://redmine.remdex.info/projects/hppg">High performance photo gallery</a></div>
	<div class="creator copyright">&copy; <?=date('Y')?></div>
</div>

<?php 
$debug = ezcDebug::getInstance(); 
echo $debug->generateOutput(); 
?>