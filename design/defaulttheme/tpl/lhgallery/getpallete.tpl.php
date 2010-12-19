<div class="pallete-filter" id="pall-comb">
<?php foreach (array_reverse(erLhcoreClassModelGalleryPallete::getList()) as $pallete) : ?>
<div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);">
<a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode('/',$pallete_id)?>/<?=$pallete->id?>"></a>
</div>
<?endforeach;?>
</div>