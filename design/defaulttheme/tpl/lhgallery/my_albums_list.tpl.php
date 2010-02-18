<?php if (isset($pages)) : ?> 
    <div class="navigator"><?=$pages->display_pages();?> <div class="right"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Found')?> - <?=$pages->items_total?></div></div>
<? endif;?>
<div class="float-break">
<? foreach ($items as $key => $item) : ?>
    <div class="album-thumb">
        <div class="content">
        <div class="albthumb-img"><?=$item->album_thumb_path;?></div>
       <div class="right">
       <a href="<?=erLhcoreClassDesign::baseurl('/gallery/editalbum/')?><?=$item->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Edit album');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('/gallery/deletealbum/')?><?=$item->aid?>" onclick="return hw.confirm('Are you sure?')"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Delete album');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('/gallery/addimages/')?><?=$item->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Add images');?>" /></a>
       </div>
       <h2><a href="<?=erLhcoreClassDesign::baseurl('/gallery/mylistalbum/')?><?=$item->aid?>" ><?=htmlspecialchars($item->title)?></a></h2>
       <?=$item->images_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','files.')?>
       </div>
    </div>   
<?endforeach; ?>      
</div>
<?php if (isset($pages)) : ?>
    <div class="navigator"><?=$pages->display_pages();?></div>
<? endif;?>