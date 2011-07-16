<div class="header-list">

<?php if (isset($palletes) || isset($npalletes)) : 


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

<?php if ($database_handler == false) : ?>
<div class="right order-nav" id="ncolor-filter-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span class="da-ind"><?php if ($nmax_filters == false) : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Exclude color');?><?php else : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Maximum');?> <?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','filters');?><?endif;?></span></a>
        <ul class="sort-box">
            <li id="npallete-content" style="background-color:#FFFFFF"></li>          
        </ul>    
</ul>
</div>
<?php endif;?>

<div class="right order-nav" id="color-filter-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span class="da-ind"><?php if ($max_filters == false) : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Include color');?><?php else : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Maximum');?> <?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','filters');?><?endif;?></span></a>
        <ul class="sort-box">
            <li id="pallete-content" style="background-color:#FFFFFF"></li>          
        </ul>    
</ul>
</div>



<?php if (!empty($pallete_id) || !empty($npallete_id)) : ?>
<br />
<br />
<?php endif;;?>


<script>
<?php if ($max_filters == false) : ?>
hw.initSortBox('#color-filter-nav');
hw.initPalleteFilter('<?=$yes_color.$no_color.$appendResolutionMode?>','color','');
<?php endif;?>

<?php if ($database_handler == false) : ?>
hw.initSortBox('#ncolor-filter-nav');
hw.initSortBox('#resolution-nav');
hw.initNPalleteFilter('<?=$yes_color.$no_color.$appendResolutionMode?>','color','');
<?php endif;?>

</script>


<?php foreach ($npallete_id as $pallete) : 
$arrayItems = $npallete_id;
unset($arrayItems[array_search($pallete,$arrayItems)]);
$palleteCurrent = $npalletes[$pallete];
?>
    <div class="csc cscr" style="background-color:rgb(<?=$palleteCurrent->red?>,<?=$palleteCurrent->green?>,<?=$palleteCurrent->blue?>)">
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?><?=$yes_color?><? if (count($arrayItems) > 0){ ?>/(ncolor)/<?=implode($arrayItems,'/').$appendResolutionMode;};?>"></a>
    </div>
<?php endforeach;?>

<?php if (count($npallete_id) > 0) : ?>
<span class="inctype exccl"  title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Excluded colors in search')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','colors')?></span>
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
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?><? if (count($arrayItems) > 0){ ?>/(color)/<?=implode($arrayItems,'/').$no_color.$appendResolutionMode;} else { echo $no_color.$appendResolutionMode; };?>"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','More color');?>" class="more-color" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode($moreColors,'/').$no_color.$appendResolutionMode;?>"></a>
    </div>
<?php endforeach;?>

<?php if (count($pallete_id) > 0) : ?>
<span class="inctype inccl"  title="Included colors in search"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','colors')?></span>
<?php endif; ?>

<?php endif;?>

<h1><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Return to pallete')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Images by color')?></a></h1>

</div>

<?php if ($show_pallete == true) : ?>

<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Choose color')?> (YES)</h2>

<div class="pallete-main float-break">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : ?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);"><a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=$pallete->id?>"></a></div>
<?endforeach;?>
</div>
<br />

<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Choose color')?> (NO)</h2>
<div class="pallete-main float-break">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : ?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);"><a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(ncolor)/<?=$pallete->id?>"></a></div>
<?endforeach;?>
</div>

<?php else : ?>

    <? if ($pages->items_total > 0) { ?>
  
       
    <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
                     
    <? } else { ?>
    
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','No records.')?></p>

<? } ?>

<?php endif;?>