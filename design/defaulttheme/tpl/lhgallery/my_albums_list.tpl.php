<div class="float-break">
<? 
$counter = 1;
foreach ($items as $item) : ?>
    <div class="album-thumb<?=!($counter % 4) ? ' left-thumb' : ''?>">
        <div class="content">        
            <div class="albthumb-img">
            <a href="<?=erLhcoreClassDesign::baseurl('gallery/mylistalbum')?>/<?=$item->aid?>"><?php if ($item->album_thumb_path !== false) :?> 
            <img src="<?=erLhcoreClassDesign::imagePath($item->album_thumb_path)?>" alt="" width="130" height="140"/>
            <?php else :?>
            <img src="<?=erLhcoreClassDesign::design("images/newdesign/nophoto.jpg")?>" alt="" width="130" height="140"/>            
            <?php endif;?></a>      
            </div>
        
        
       <div class="tit-item">
       <h2><a title="<?=htmlspecialchars($item->title)?>" href="<?=erLhcoreClassDesign::baseurl('gallery/mylistalbum')?>/<?=$item->aid?>"><?=htmlspecialchars($item->title)?></a></h2>      
       </div>
       
       <span class="files-ico" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','files')?>">
        <?=$item->images_count;?>
       </span>
       <div class="right">
               <a href="<?=erLhcoreClassDesign::baseurl('gallery/albumedit')?>/<?=$item->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Edit album');?>" /></a>
               <a href="<?=erLhcoreClassDesign::baseurl('gallery/deletealbum')?>/<?=$item->aid?>" onclick="return hw.confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Are you sure?')?>')"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Delete album');?>" /></a>
               <a href="<?=erLhcoreClassDesign::baseurl('gallery/addimages')?>/<?=$item->aid?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_albums_list','Add images');?>" /></a>    
       </div>
       </div>
                   
    </div>   
<?
$counter++;
endforeach; ?> 
</div>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>