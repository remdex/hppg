<div class="pallete-filter" id="pall-comb">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : 

$moreColors = $pallete_id;
$moreColors[] = $pallete->id;
sort($moreColors);
?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);">
<?php if ($mode == 'color') : ?>
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode('/',$moreColors).$no_color.$resolution.$match.$album?>"></a>
<?php elseif ($mode == 'search') : ?>
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/<?=urlencode($keyword)?>/(color)/<?=implode('/',$moreColors).$no_color.$resolution.$match.$album?>"></a>    
<?php endif;?>
</div>
<?endforeach;?>
</div>