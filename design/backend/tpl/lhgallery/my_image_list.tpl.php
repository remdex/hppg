<?php if (isset($pages)) : ?>
    <div class="navigator">
    <div class="right"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Found')?> - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?>
    </div>
<? endif;?> 

<div class="float-break">
<? foreach ($items as $key => $item) : ?>
    <div class="thumb-edit" id="image_thumb_<?=$item->pid?>">
        <div class="left">
            <label><input type="checkbox" name="PhotoID[]" value="<?=$item->pid?>" /> Select image</label>
        </div>
        <div class="right">
        <a class="cursor" onclick="return hw.deletePhoto(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Delete image');?>" /></a>
        </div>
        <div class="thumb-pic">
            <a href="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>">
            
            <?php if ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>
                <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
            <?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV) : ?>        
                                    
                <?php if ($item->has_preview) : ?>
                    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace('.ogv','.jpg',$item->filename)),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
                <?php else : ?>
                    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/ogv.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
                <?php endif;?>
                
            <?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF) : ?>                               
               
                    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/swf.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
                
            <?php endif;?>
            
            
            </a>
        </div>
        <div class="thumb-attr">              
           
                <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Title')?></div>				
				<input type="text" id="PhotoTitle_<?=$item->pid?>" value="<?=htmlspecialchars($item->title)?>" class="inputfield" />

				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Keywords')?></div>	
				<input type="text" id="PhotoKeyword_<?=$item->pid?>" value="<?=htmlspecialchars($item->keywords)?>" class="inputfield" />
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Approved')?></div>	
				<input type="checkbox" id="PhotoApproved_<?=$item->pid?>" <?=$item->approved == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Cross eye image')?></div>	
				<input type="checkbox" id="PhotoAnaglyph_<?=$item->pid?>" <?=$item->anaglyph == 1 ? 'checked="checked"' : ''?> class="inputfield" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Caption')?></div>			
				<textarea class="default-textarea" id="PhotoDescription_<?=$item->pid?>"><?=htmlspecialchars($item->caption)?></textarea>	  
				<input type="button" onclick="hw.updatePhoto(<?=$item->pid?>)"class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Update');?>" /><span class="status-img" id="image_status_<?=$item->pid?>"></span>            
        </div>
    </div>   
<?endforeach; ?>  
</div>

<?php if (isset($pages)) : ?>
    <div class="navigator"><?=$pages->display_pages();?></div>
<? endif;?>