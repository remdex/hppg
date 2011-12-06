<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Replace image');?></h1></div>

<?php if (isset($image_replace)) : ?>

<?php if ($type !== 'full') : ?>
<div id="image-new-data" style="display:none">
<?php $item = $image ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?> 
</div>
<script>
parent.$('#pid_thumb_<?=$image->pid?> > a').html($('#image-new-data').html());
parent.$.colorbox.close();
</script>
<?php else : ?>

<div id="image-new-data" style="display:none">
<div class="img">
<?php if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>

    <a onclick="return hw.showFullImage($(this))" rel="<?=$image->pwidth?>" href="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>"><img class="main" src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Click to see fullsize')?>" ></a>

<?php elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV ) : ?>

    <video src="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>" width="<?=$image->pwidth?>" height="<?=$image->pheight?>" controls="true" type='video/ogv' <?= $image->has_preview == 1 ? 'poster="'.erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode(str_replace('.ogv','.jpg',$image->filename))).'"' : ''?> <?=erLhcoreClassModelSystemConfig::fetch('loop_video')->current_value == 1 ? 'loop' :''?> ></video>
    
<?php elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF ) : ?>

    <object width="<?=$image->pwidth?>" height="<?=$image->pheight?>">
    <param name="movie" value="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>">
    <embed src="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>" width="<?=$image->pwidth?>" height="<?=$image->pheight?>">
    </embed>
    </object>
        
<?php elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeFLV ) : ?>
    <object id="monFlash" type="application/x-shockwave-flash" data="<?=erLhcoreClassDesign::design('js/player_flv_maxi.swf')?>" width="<?=$image->pwidth?>" height="<?=$image->pheight?>">
		<param name="movie" value="<?=erLhcoreClassDesign::design('js/player_flv_maxi.swf')?>" />
		<param name="allowFullScreen" value="true" />
		<param name="FlashVars" value="flv=<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>&amp;width=<?=$image->pwidth?>&amp;height=<?=$image->pheight?>&amp;startimage=<?=erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode(str_replace('.flv','.jpg',$image->filename)))?>&amp;showstop=1&amp;showvolume=1&amp;showtime=1&amp;bgcolor=F1F1F1" />
		<p>Your browser does not support flash player</p>
	</object>
	
<?php elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeVIDEO ) : ?>
    <object id="monFlash" type="application/x-shockwave-flash" data="<?=erLhcoreClassDesign::design('js/player_flv_maxi.swf')?>" width="<?=$image->pwidth?>" height="<?=$image->pheight?>">
		<param name="movie" value="<?=erLhcoreClassDesign::design('js/player_flv_maxi.swf')?>" />
		<param name="allowFullScreen" value="true" />
		<param name="FlashVars" value="flv=<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode(str_replace(array('.avi','.mpg','.mpeg','.wmv','.mp4'),'.flv',$image->filename)))?>&amp;width=<?=$image->pwidth?>&amp;height=<?=$image->pheight?>&amp;startimage=<?=erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode(str_replace(array('.avi','.mpg','.mpeg','.mp4','.wmv'),'.jpg',$image->filename)))?>&amp;showstop=1&amp;showvolume=1&amp;showtime=1&amp;bgcolor=F1F1F1" />
		<p>Your browser does not support flash player</p>
	</object>	
<?php endif;?>

<?php if( $image->caption != '') : ?>
<div class="float-break cap-img"><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($image->caption))?></div>
<?endif;?>

</div>

<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_window_colors_data.tpl.php'));?>

<?php if ((erConfigClassLhConfig::getInstance()->getSetting( 'site', 'extract_exif_data' ) == true) && $image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_window_exif_data.tpl.php'));?>
<?php endif;?>
</div>

<script>
parent.$('#img-view').html($('#image-new-data').html());
parent.$.colorbox.close();
</script>

<?php endif;?>


<?php else :?>

<?php if (isset($errors)) : ?>
<? foreach ((array)$errors as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
<? endforeach; ?>
<?php endif;?>

<form action="" method="post" enctype="multipart/form-data">
<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','File')?></div>				
<input type="file" name="Filedata" class="inputfield" />

<div>
<input name="UploadPhoto" type="submit" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Upload');?>" />
</div>
</form>
<?php endif;?>