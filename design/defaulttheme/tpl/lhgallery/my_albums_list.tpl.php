<?php if (isset($pages)) : ?> 
    <div class="navigator"><?=$pages->display_pages();?> <div class="right"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('rss/category',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, Found - <?=$pages->items_total?></div></div>
<? endif;?>
<div class="float-break">
<? foreach ($items as $key => $item) : ?>
    <div class="album-thumb">
        <div class="content">
        <div class="albthumb-img"><?=$item->album_thumb_path;?></div>
       <div class="right">
       <a href="<?=erLhcoreClassDesign::baseurl('/gallery/editalbum/')?><?=$item->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit album');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('/gallery/deletealbum/')?><?=$item->aid?>" onclick="return hw.confirm('Are you sure?')"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete album');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('/gallery/addimages/')?><?=$item->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Add images');?>" /></a>
       </div>
       <h2><a href="<?=erLhcoreClassDesign::baseurl('/gallery/mylistalbum/')?><?=$item->aid?>" ><?=htmlspecialchars($item->title)?></a>
       
       </h2>
       <?=$item->images_count;?> files. 
       </div>
    </div>   
<?endforeach; ?>      
</div>
<?php if (isset($pages)) : ?>
    <div class="navigator"><?=$pages->display_pages();?></div>
<? endif;?>