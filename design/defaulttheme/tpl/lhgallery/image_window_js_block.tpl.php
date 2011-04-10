<script type="text/javascript">
hw.setAppendURL('<?=$urlAppend?>');
hw.initAjaxNavigation();
$('.ad-fv').click(function(){
    hw.addToFavorites(<?=$image->pid?>);
   return false;
});
<?php if ($image->anaglyph == 1) : ?>
$('.ad-anaglyph').colorbox({width:'<?=$image->pwidth+50?>px',height:'<?=$image->pheight+130?>px'});
<?php endif;?>
hw.initInfoWindow('<?=base64_encode($urlAppend)?>');   
</script>