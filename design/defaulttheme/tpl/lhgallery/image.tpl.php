<div class="image-full">
    <div class="image-full-content">
    
        <div class="header-list">
            <h1><?=htmlspecialchars($image->name_user)?></h1>
        </div>
        
        <div class="navigator float-break">
        
            <?php if ($hasPreviousImage === true) : ?>
                <a class="left-image" title="<?=htmlspecialchars($prevImage->name_user)?>" href="<?=$prevImage->url_path.$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>
            <?php endif;?>
            
            <?php if ($hasNextImage === true) : ?>
                <a class="right-image" title="<?=htmlspecialchars($nextImage->name_user)?>" href="<?=$nextImage->url_path.$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a>
            <?php endif;?>
            
            <a class="ret-thumb" href="<?=$urlReturnToThumbnails?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?></a>
        
        </div>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_window.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_control_block.tpl.php'));?>  
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/ajax_navigation_block.tpl.php'));?>  
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_window_js_block.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_details_block.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/picture_voting_block.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_comment_block.tpl.php'));?>
    
    </div>
</div>