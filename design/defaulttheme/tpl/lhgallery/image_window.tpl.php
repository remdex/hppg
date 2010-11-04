<div class="float-break">
<div class="img">

<?php if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>

    <a id="photo_full" href="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>"><img src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Click to see fullsize')?>" ></a>

<?php elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV ) : ?>

    <video src="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>" width="<?=$image->pwidth?>" height="<?=$image->pheight?>" controls="true" type='video/ogv' <?= $image->has_preview == 1 ? 'poster="'.erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode(str_replace('.ogv','.jpg',$image->filename))).'"' : ''?> <?=erLhcoreClassModelSystemConfig::fetch('loop_video')->current_value == 1 ? 'loop' :''?> ></video>
    
<?php elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF ) : ?>

    <object width="<?=$image->pwidth?>" height="<?=$image->pheight?>">
    <param name="movie" value="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>">
    <embed src="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>" width="<?=$image->pwidth?>" height="<?=$image->pheight?>">
    </embed>
    </object>
    
<?php endif;?>

<?php if( $image->caption != '') : ?>
<div class="float-break cap-img"><?=nl2br(htmlspecialchars($image->caption))?></div>
<?endif;?>
</div>
</div>