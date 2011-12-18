<div class="pallete-filter" id="npall-comb">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : 

$moreColors = $pallete_id;

$yesColor = '';
if (!in_array($pallete->id,$moreColors)){
    $moreColors[] = $pallete->id;
} else {
    unset($moreColors[array_search($pallete->id,$moreColors)]);    
}
sort($moreColors);

if ( count($moreColors) > 0 ) {
    $yesColor = '/(ncolor)/'.implode('/',$moreColors);
};

?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);">
<?php if ($mode == 'color') : ?>
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?><?=$no_color.$yesColor.$resolution.$match.$album?>"></a>
<?php elseif ($mode == 'search') : ?>
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/<?=urlencode($keyword)?><?=$no_color.$yesColor.$resolution.$match.$album?>"></a>    
<?php endif;?>
</div>
<?endforeach;?>
</div>