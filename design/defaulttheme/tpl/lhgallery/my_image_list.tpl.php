<div class="float-break">
<? 
$counter = 1;
$canApproveSelfImages = erLhcoreClassUser::instance()->hasAccessTo('lhgallery','can_approve_self_photos');


foreach ($items as $key => $item) : ?>

<div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?> thumb-edit" id="image_thumb_<?=$item->pid?>">
    <div class="thumb-pic">
        <a href="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>"><img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>           
    </div>
    <div class="thumb-attr">
    
    <div class="tit-item">
        <h3><a title="<?=htmlspecialchars($item->name_user);?>" rel="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>" href="<?=erLhcoreClassDesign::imagePath($item->filepath.$item->filename)?>">
            <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
            </a>
        </h3>
    </div>
    
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Title')?></div>				
				<input type="text" id="PhotoTitle_<?=$item->pid?>" value="<?=htmlspecialchars($item->title)?>" class="inputfield" />

				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Keywords')?></div>	
				<input type="text" id="PhotoKeyword_<?=$item->pid?>" value="<?=htmlspecialchars($item->keywords)?>" class="inputfield" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Cross eye image')?></div>	
				<input type="checkbox" id="PhotoAnaglyph_<?=$item->pid?>" <?=$item->anaglyph == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Approved')?></div>	
				<input type="checkbox" id="PhotoApproved_<?=$item->pid?>" <?=$canApproveSelfImages == false ? 'disabled="disabled"' : ''?> <?=$item->approved == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
							
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Caption')?></div>			
				<textarea class="default-textarea" id="PhotoDescription_<?=$item->pid?>"><?=htmlspecialchars($item->caption)?></textarea>	  
    
    <div class="right">
        <a class="cursor" onclick="return hw.deletePhoto(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Delete image');?>" /></a>
    </div>               
    
    <input type="button" onclick="hw.updatePhoto(<?=$item->pid?>)"class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Update');?>" /><span class="status-img" id="image_status_<?=$item->pid?>"></span>            
    
    
    </div>
</div>
     
<?$counter++;endforeach; ?>  
</div>

<?php if (isset($pages)) : ?>
 <div class="nav-container">
    <div class="navigator">
    <div class="right found-total"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Found')?> - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?></div>
</div>
<? endif;?>