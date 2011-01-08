<div class="header-list">

<?php if (isset($palletes)) : 


$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );
$resolutionAppend = '';
if (isset($currentResolution) && key_exists($currentResolution,$resolutions)) {    
    $resolutionAppend = '/(resolution)/'.$resolutions[$currentResolution]['width'].'x'.$resolutions[$currentResolution]['height'];
}

?> 

<?php if ($database_handler == false) : ?>
<div class="right order-nav" id="resolution-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <?php $currentResolution?>
            <li><a <?=$currentResolution == '' ? 'class="selor" ' : ''?> href="<?=$urlSortBase?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Any resolution')?></a>
            <?php foreach ($resolutions as $key => $resolution) : ?>
                <li><a <?=$currentResolution == $key ? 'class="selor" ' : ''?>href="<?=$urlSortBase?>/(resolution)/<?=$resolution['width']?>x<?=$resolution['height']?>"><span><?=$resolution['width']?>x<?=$resolution['height']?></span></a>
            <?php endforeach;?>            
        </ul>    
</ul>
</div>
<?php endif;?>

<div class="right order-nav" id="color-filter-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span class="da-ind"><?php if ($max_filters == false) : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Append color filter');?><?php else : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Maximum');?> <?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','filters');?><?endif;?></span></a>
        <ul class="sort-box">
            <li id="pallete-content" style="background-color:#FFFFFF"></li>          
        </ul>    
</ul>
</div>

<?php if ($max_filters == false) : ?>
<script>
hw.initSortBox('#color-filter-nav');

<?php if ($database_handler == false) : ?>
hw.initSortBox('#resolution-nav');
<?php endif;?>

hw.initPalleteFilter('/<?=implode('/',$pallete_id).$appendResolutionMode?>','color','');
</script>
<?php endif;?>

<?php 
foreach ($pallete_id as $pallete) : 
$arrayItems = $pallete_id;
unset($arrayItems[array_search($pallete,$arrayItems)]);
$palleteCurrent = $palletes[$pallete];

$moreColors = $arrayItems;
$moreColors[] = $pallete;
$moreColors[] = $pallete;
sort($moreColors);

?>
    <div class="csc" style="background-color:rgb(<?=$palleteCurrent->red?>,<?=$palleteCurrent->green?>,<?=$palleteCurrent->blue?>)">
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?><? if (count($arrayItems) > 0){ ?>/(color)/<?=implode($arrayItems,'/').$appendResolutionMode;};?>"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','More color');?>" class="more-color" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode($moreColors,'/').$appendResolutionMode;?>"></a>
    </div>
<?php endforeach;?>

<?php endif;?>

<h1><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Return to pallete')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Images by color')?></a></h1>

</div>

<?php if ($show_pallete == true) : ?>

<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Choose color')?></h2>
<div class="pallete-main">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : ?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);"><a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=$pallete->id?>"></a></div>
<?endforeach;?>
</div>




<?php else : ?>

    <? if ($pages->items_total > 0) { ?>
  
       
    <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
                     
    <? } else { ?>
    
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','No records.')?></p>

<? } ?>

<?php endif;?>