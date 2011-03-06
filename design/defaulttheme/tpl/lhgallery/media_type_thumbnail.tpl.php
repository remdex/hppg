<?php if ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>
    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
<?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV) : ?>        
                        
    <?php if ($item->has_preview) : ?>
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace('.ogv','.jpg',$item->filename)),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
    <?php else : ?>
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/ogv.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
    <?php endif;?>
    
<?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF) : ?>                               
   
    <?php if ($item->has_preview) : ?>
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace('.swf','.jpg',$item->filename)),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
    <?php else : ?>
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/swf.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
    <?php endif;?>

<?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeFLV) : ?>                               
   
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace('.flv','.jpg',$item->filename)),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">

<?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeVIDEO) : ?>                               
   
    <?php if ($item->has_preview) : ?>
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace(array('.avi','.mpg','.mpeg','.wmv'),'.jpg',$item->filename)),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
    <?php else : ?>
        <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/avi.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
    <?php endif;?>
    
<?php endif;?>