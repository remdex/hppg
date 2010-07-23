<? if (isset($Result['path'])) : 		
$pathElementCount = count($Result['path'])-1;
?>			
<div id="path"><a href="/"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?></a> &raquo; <? foreach ($Result['path'] as $key => $pathItem) : $pathElementRaquo = ($key != $pathElementCount) ? '&raquo;' : '';if (isset($pathItem['url'])) { ?><a href="<?=$pathItem['url']?>"><?=$pathItem['title']?></a> <?=$pathElementRaquo;?> <? } else { ?><?=$pathItem['title']?> <?=$pathElementRaquo;?><? }; ?><? endforeach; ?></div><? endif; ?>