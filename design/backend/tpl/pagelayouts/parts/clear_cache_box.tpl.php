<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clear cache')?></legend> 
<ul>
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean all cache');?></a></li>   
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/cachestatus')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Cache status');?></a></li>   
</ul>
</fieldset>