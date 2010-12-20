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
hw.initPalleteFilter('<?=count($pallete_id) > 0 ? '/'.implode('/',$pallete_id) : ''?>','<?=isset($modePallete) ? $modePallete : 'color'?>','<?=isset($keyword) ? $keyword : ''?>');
</script>
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
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=$modePallete == 'color' ? erLhcoreClassDesign::baseurl('gallery/color') : erLhcoreClassDesign::baseurl('gallery/search') .'/(keyword)/'.urlencode($keyword) ?><?=count($arrayItems) > 0 ? '/(color)/'.implode($arrayItems,'/') : ''?>"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','More color');?>" class="more-color" href="<?=$modePallete == 'color' ? erLhcoreClassDesign::baseurl('gallery/color') : erLhcoreClassDesign::baseurl('gallery/search') .'/(keyword)/'.urlencode($keyword) ?>/(color)/<?=implode($moreColors,'/')?>"></a>
    </div>
<?php endforeach;?>