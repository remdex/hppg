<?php if (isset($pages) && $pages->num_pages > 1) : ?>
<div class="nav-container">
    <div class="navigator">
    <div class="right found-total"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Found')?> - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?></div>
</div>
<?php endif;?>