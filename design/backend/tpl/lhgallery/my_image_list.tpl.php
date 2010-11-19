<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?> 

<div class="float-break">
<? foreach ($items as $key => $item) : 
?>
    <div class="thumb-edit" id="image_thumb_<?=$item->pid?>">
        <div class="left">
            <label><input type="checkbox" name="PhotoID[]" value="<?=$item->pid?>" /> Select image</label>
        </div>
        <div class="right">
        <a class="cursor" onclick="return hw.deletePhoto(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Delete image');?>" /></a>
        </div>
        <div class="thumb-pic">
            <a href="<?=$item->url_path.$appendImageMode?>">
            
            <?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?>
            
            
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
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>