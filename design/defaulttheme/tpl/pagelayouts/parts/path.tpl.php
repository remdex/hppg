<? if (isset($Result['path'])) : 		
$pathElementCount = count($Result['path'])-1;
if ($pathElementCount >= 0):
?>			
<div id="path"><a href="<?=erLhcoreClassDesign::baseurl()?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?></a><? foreach ($Result['path'] as $key => $pathItem) : if (isset($pathItem['url']) && $pathElementCount != $key) { ?><a href="<?=$pathItem['url']?>"><?=htmlspecialchars($pathItem['title'])?></a><? } else { ?><?=htmlspecialchars($pathItem['title'])?><? }; ?><? endforeach; ?></div><? endif; ?>
<?php endif;?>