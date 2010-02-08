<?php if (isset($pages)) : ?>
    <div class="navigator">
    <div class="right"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('rss/category',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, Found - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?>
    </div>
<? endif;?>

<div class="float-break">
<? foreach ($items as $key => $item) : ?>
    <div class="thumb-edit" id="image_thumb_<?=$item->pid?>">
        <div class="right">
        <a class="cursor" onclick="return hw.deletePhoto(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" /></a>
        </div>
        <div class="thumb-pic">
            <a href="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>"><img title="See full size" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename))?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>
        </div>
        <div class="thumb-attr">              
           
                <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Photo title')?></div>				
				<input type="text" id="PhotoTitle_<?=$item->pid?>" value="<?=htmlspecialchars($item->title)?>" class="inputfield" />

				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Photo keywords')?></div>	
				<input type="text" id="PhotoKeyword_<?=$item->pid?>" value="<?=htmlspecialchars($item->keywords)?>" class="inputfield" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Caption')?></div>			
				<textarea class="default-textarea" id="PhotoDescription_<?=$item->pid?>"><?=htmlspecialchars($item->caption)?></textarea>	  
				<input type="button" onclick="hw.updatePhoto(<?=$item->pid?>)"class="default-button" value="Update" /><span class="status-img" id="image_status_<?=$item->pid?>"></span>            
        </div>
    </div>   
<?endforeach; ?>  
</div>

<?php if (isset($pages)) : ?>
    <div class="navigator"><?=$pages->display_pages();?></div>
<? endif;?>