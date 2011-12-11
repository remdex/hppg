<script type="text/javascript">
 var _lactq = _lactq || [];
_lactq.push({'f':'hw_set_append_url','a':['<?=$urlAppend?>']});
_lactq.push({'f':'init_ajax_navigation','a':[]});
_lactq.push({'f':'init_add_fav','a':['<?=$image->pid?>']});
<?php if ($image->anaglyph == 1) : ?>
_lactq.push({'f':'init_anaglyph_action','a':[{width:'<?=$image->pwidth+50?>px',height:'<?=$image->pheight+130?>px'}]});
<?php endif;?>
_lactq.push({'f':'hw_init_info_window','a':['<?=base64_encode($urlAppend)?>']});
</script>