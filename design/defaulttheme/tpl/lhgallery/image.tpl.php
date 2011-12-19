<div class="image-full">
    <div class="image-full-content"  itemscope itemtype="schema.org/ImageObject">
    
        <div class="header-list">
            <h1 itemprop="name"><?=htmlspecialchars($image->name_user)?></h1>
        </div>
        
        <div class="navigator-image float-break">
                    
        
            <?php                        
            if ($hasNextImage === true) : ?>            
                <a class="right-image" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?> - <?=htmlspecialchars($nextImage->name_user)?>" href="<?=$nextImage->url_path.$urlAppend?>"></a>
            <?php endif;?>
            
            <?php if ($hasPreviousImage === true) : ?>
                <a class="left-image" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?> - <?=htmlspecialchars($prevImage->name_user)?>" href="<?=$prevImage->url_path.$urlAppend?>"></a>
            <?php endif;?>
                        
            <a class="ret-thumb" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?>" href="<?=$urlReturnToThumbnails?>"></a>
        
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