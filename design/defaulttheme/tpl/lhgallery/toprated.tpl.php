<div class="header-list">
<a class="rss_list" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','Top rated images')?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/topratedrss/')?>"></a><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','Top rated images')?></h1>
</div>
<? if ($pages->items_total > 0) { ?>
         
<? 
    $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'sort' => 'pic_rating DESC, votes DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
    $appendImageMode = '/(mode)/toprated';
?>   
   
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
          
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/toprated','No records')?>.</p>

<? } ?>

