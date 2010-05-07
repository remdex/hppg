<?php if (isset($pages)) : ?> 
    <div class="navigator"><?=$pages->display_pages();?> <div class="right"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Page %currentpage of %totalpage',array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Found')?> - <?=$pages->items_total?></div></div>
<? endif;?>
<div class="float-break">

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Title');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach ($items as $key => $item) : ?>
    <tr>
        <td width="1%"><?=$item->aid?></td>
        <td>
        <div class="albthumb-img right"><?=$item->album_thumb_path;?></div>
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages/')?><?=$item->aid?>"><?=htmlspecialchars($item->title)?></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/addimagesadmin/')?><?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Add images');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/albumeditadmin/')?><?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Edit album');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/deletealbumadmin/')?><?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Delete album');?>" /></a></td>
    </tr>  
<?endforeach; ?>  
</table><br />  

</div>
<?php if (isset($pages)) : ?>
    <div class="navigator"><?=$pages->display_pages();?></div>
<? endif;?>