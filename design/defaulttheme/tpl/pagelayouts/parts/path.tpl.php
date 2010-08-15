<? if (isset($Result['path'])) : 		
$pathElementCount = count($Result['path'])-1;
if ($pathElementCount >= 0):
?>			
<div id="path"><a href="/"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?></a><? foreach ($Result['path'] as $key => $pathItem) : if (isset($pathItem['url'])) { ?><a href="<?=$pathItem['url']?>"><?=$pathItem['title']?></a><? } else { ?><?=$pathItem['title']?><? }; ?><? endforeach; ?></div><? endif; ?>
<?php endif;?>