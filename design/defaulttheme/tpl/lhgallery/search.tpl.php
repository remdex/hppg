<div class="header-list">
<div class="right order-nav">
    <a class="da<?=$mode == 'newdesc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded first')?></a>
    <a class="ar<?=$mode == 'newasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/newasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded last')?></a>    
    <a class="da<?=$mode == 'popular' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/popular"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular first')?></a>
    <a class="ar<?=$mode == 'popularasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/popularasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular last')?></a>
    <a class="da<?=$mode == 'lasthits' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/lasthits"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits first')?></a>
    <a class="ar<?=$mode == 'lasthitsasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/lasthitsasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits last')?></a>    
    <a class="da<?=$mode == 'toprated' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/toprated"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated first')?></a>
    <a class="ar<?=$mode == 'topratedasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/topratedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated last')?></a>    
    <a class="da<?=$mode == 'lastcommented' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/lastcommented"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented first')?></a>
    <a class="ar<?=$mode == 'lastcommentedasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>/(keyword)/<?echo urlencode($keyword)?>/(sort)/lastcommentedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented last')?></a>
</div>
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Search results')?> - <?=htmlspecialchars($keyword)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>

  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
  
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Nothing found')?>...</p>

<? } ?>

