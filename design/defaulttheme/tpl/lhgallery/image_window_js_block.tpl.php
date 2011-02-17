<script type="text/javascript">

hw.setAppendURL('<?=$urlAppend?>');
$('.right-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'right');
   return false;
});
$('.left-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'left');
   return false;
});
$('.ad-fv').click(function(){
    hw.addToFavorites(<?=$image->pid?>);
   return false;
});

$('.ad-html').colorbox();
$('.ad-phpbb').colorbox();

<?php if ($image->anaglyph == 1) : ?>
$('.ad-anaglyph').colorbox({width:'<?=$image->pwidth+50?>px',height:'<?=$image->pheight+130?>px'});
<?php endif;?>
hw.initInfoWindow('<?=urlencode($urlAppend)?>');
</script>