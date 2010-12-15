<div class="header-list">

<?php if (isset($pallete)) : ?> 
<div class="csc" style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>)"><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Return to pallete')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>"></a></div>
<?php endif;?>

<h1><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Return to pallete')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Images by color')?></a></h1>

</div>

<?php if ($show_pallete == true) : ?>
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','Choose color')?></h2>
<div class="pallete-main">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : ?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);"><a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/<?=$pallete->id?>"></a></div>
<?endforeach;?>
</div>

<?php else : ?>

    <? if ($pages->items_total > 0) { ?>
             
    <? 
        $items = erLhcoreClassModelGalleryPallete::getImages(array('filter' => array('pallete_id' => $pallete_id),'sort' => 'count DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
    ?>   
       
    <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
                     
    <? } else { ?>
    
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/color','No records.')?></p>

<? } ?>

<?php endif;?>