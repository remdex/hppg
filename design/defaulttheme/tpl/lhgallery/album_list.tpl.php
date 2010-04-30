<?php if (isset($pages)) : ?> 
    <div class="navigator"><?if ($pages->num_pages > 1) : ?><?=$pages->display_pages();?><?php endif;?> <div class="right"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','Page %currentpage of %totalpage',array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','Found')?> - <?=$pages->items_total?></div></div>
<? endif;?>
<div class="float-break">
<? foreach ($items as $key => $item) : ?>
    <div class="album-thumb">
        <div class="content">
        <div class="albthumb-img"><?=$item->album_thumb_path;?></div>
       <h2><a href="<?=$item->url_path?>"><?=htmlspecialchars($item->title)?></a></h2>
       <?=$item->images_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','files')?>.
       
       </div>
    </div>   
<?endforeach; ?>   
<?php if (isset($pages) && $pages->num_pages > 1 && !isset($noBottom)) : ?>
    <div class="navigator" style="clear:left;"><?=$pages->display_pages();?></div>
<? endif;?> 
</div>
