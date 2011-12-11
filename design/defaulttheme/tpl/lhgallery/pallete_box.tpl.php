<div class="right order-nav" id="ncolor-filter-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span class="da-ind"><?php if ($nmax_filters == false) : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Exclude color');?><?php else : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Maximum');?> <?=erConfigClassLhConfig::getInstance()->getSetting( 'color_search', 'maximum_filters');?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','filters');?><?endif;?></span></a>
        <ul class="sort-box">
            <li id="npallete-content" style="background-color:#FFFFFF"></li>          
        </ul>    
</ul>
</div>

<div class="right order-nav" id="color-filter-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span class="da-ind"><?php if ($max_filters == false) : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Include color');?><?php else : ?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Maximum');?> <?=erConfigClassLhConfig::getInstance()->getSetting( 'color_search', 'maximum_filters');?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','filters');?><?endif;?></span></a>
        <ul class="sort-box">
            <li id="pallete-content" style="background-color:#FFFFFF"></li>          
        </ul>    
</ul>
</div>

<script>
var _lactq = _lactq || [];

<?php if ($max_filters == false) : ?>
_lactq.push({'f':'hw_init_sort_box','a':['#color-filter-nav']});
_lactq.push({'f':'init_pallete_filter','a':['<?=$yes_color.$no_color?>','<?=isset($modePallete) ? $modePallete : 'color'?>','<?=isset($keyword) ? $keyword . $resolutionAppend . $matchModeAppend : ''?>']});
<?php endif;?>

<?php if ($nmax_filters == false) : ?>
_lactq.push({'f':'hw_init_sort_box','a':['#ncolor-filter-nav']});
_lactq.push({'f':'init_npallete_filter','a':['<?=$yes_color.$no_color?>','<?=isset($modePallete) ? $modePallete : 'color'?>','<?=isset($keyword) ? $keyword . $resolutionAppend . $matchModeAppend : ''?>']});
<?php endif;?>
</script>


<br />
<br />
<?php foreach ($npallete_id as $pallete) : 
$arrayItems = $npallete_id;
unset($arrayItems[array_search($pallete,$arrayItems)]);
$palleteCurrent = $npalletes[$pallete]; ?>
    <div class="csc cscr" style="background-color:rgb(<?=$palleteCurrent->red?>,<?=$palleteCurrent->green?>,<?=$palleteCurrent->blue?>)">
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=$modePallete == 'color' ? erLhcoreClassDesign::baseurl('gallery/color') : erLhcoreClassDesign::baseurl('gallery/search') .'/(keyword)/'.urlencode($keyword) ?><?=$yes_color?><?=count($arrayItems) > 0 ? '/(ncolor)/'.implode($arrayItems,'/') : ''?><?echo $resolutionAppend,$matchModeAppend?>"></a>
    </div>
<?php endforeach;?>

<?php if (count($npallete_id) > 0) : ?>
<span class="inctype exccl" title="Excluded colors in search"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','colors')?></span>
<?php endif;?>


<?php foreach ($pallete_id as $pallete) : 
$arrayItems = $pallete_id;
unset($arrayItems[array_search($pallete,$arrayItems)]);
$palleteCurrent = $palletes[$pallete];

$moreColors = $arrayItems;
$moreColors[] = $pallete;
$moreColors[] = $pallete;
sort($moreColors);

?>
    <div class="csc" style="background-color:rgb(<?=$palleteCurrent->red?>,<?=$palleteCurrent->green?>,<?=$palleteCurrent->blue?>)">
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=$modePallete == 'color' ? erLhcoreClassDesign::baseurl('gallery/color') : erLhcoreClassDesign::baseurl('gallery/search') .'/(keyword)/'.urlencode($keyword) ?><?=count($arrayItems) > 0 ? '/(color)/'.implode($arrayItems,'/') : ''?><?echo $no_color,$resolutionAppend,$matchModeAppend?>"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','More color');?>" class="more-color" href="<?=$modePallete == 'color' ? erLhcoreClassDesign::baseurl('gallery/color') : erLhcoreClassDesign::baseurl('gallery/search') .'/(keyword)/'.urlencode($keyword) ?>/(color)/<?=implode($moreColors,'/')?><?echo $no_color,$resolutionAppend,$matchModeAppend?>"></a>
    </div>
<?php endforeach;?>
<?php if (count($pallete_id) > 0) : ?>
<span class="inctype inccl" title="Included colors in search"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','colors')?></span>
<?php endif; ?>


