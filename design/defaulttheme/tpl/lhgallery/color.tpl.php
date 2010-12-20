<div class="header-list">

<?php if (isset($palletes)) : ?> 

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
hw.initPalleteFilter('/<?=implode('/',$pallete_id)?>','color','');
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
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Remove color')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?><? if (count($arrayItems) > 0){ ?>/(color)/<?=implode($arrayItems,'/');};?>"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','More color');?>" class="more-color" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode($moreColors,'/');?>"></a>
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
             
    <?     
        $items = erLhcoreClassModelGalleryPallete::getImages(array('pallete_id' => $pallete_id,'sort' => 'lh_gallery_pallete_images.count DESC, lh_gallery_pallete_images.pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
    ?>   
       
    <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
                     
    <? } else { ?>
    
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','No records.')?></p>

<? } ?>

<?php endif;?>