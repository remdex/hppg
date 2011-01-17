<div class="image-full">
    <div class="image-full-content">
    
        <div class="header-list">
            <h1><?=htmlspecialchars($image->name_user)?></h1>
        </div>
                        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_window.tpl.php'));?>
               
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_details_block.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/picture_voting_block.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_comment_block_admin.tpl.php'));?>
        
    </div>
</div>