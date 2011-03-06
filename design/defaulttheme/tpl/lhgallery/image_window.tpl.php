<div class="float-break" id="img-view">

<div class="img">
<?php if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>

    <a onclick="return hw.showFullImage($(this))" rel="<?=$image->pwidth?>" href="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>"><img src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Click to see fullsize')?>" ></a>

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
		<param name="FlashVars" value="flv=<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode(str_replace(array('.avi','.mpg','.mpeg','.wmv'),'.flv',$image->filename)))?>&amp;width=<?=$image->pwidth?>&amp;height=<?=$image->pheight?>&amp;startimage=<?=erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode(str_replace(array('.avi','.mpg','.mpeg'),'.jpg',$image->filename)))?>&amp;showstop=1&amp;showvolume=1&amp;showtime=1&amp;bgcolor=F1F1F1" />
		<p>Your browser does not support flash player</p>
	</object>	
<?php endif;?>

<?php if( $image->caption != '') : ?>
<div class="float-break cap-img"><?=erLhcoreClassGallery::make_clickable(nl2br(htmlspecialchars($image->caption)))?></div>
<?endif;?>

<a href="javascript:void(0)" onclick="$.colorbox({href:'<?=erLhcoreClassDesign::baseurl('issue/report')?>/<?=$image->pid?>'});">Report issue within an image</a>

</div>

<?php $colorsDominant = erLhcoreClassModelGalleryPallete::getPictureDominantColors($image->pid,10); if (count($colorsDominant) > 0) : ?>
<div class="dominant-colors hide-full">
    <?php 
    $topThreeColors = array();
    $lastID = -1;   
    foreach (erLhcoreClassModelGalleryPallete::getPictureDominantColors($image->pid,10) as $pallete) : 
    if (count($topThreeColors) < 3 && ($lastID == -1 || abs($pallete->id - $lastID) > 10)) {
        
        $blocked = false;
        foreach ($topThreeColors as $color_id) { // Hard check to avoid appending similar collor to top three colors
            if (abs($pallete->id - $color_id) <= 10){
                $blocked = true;
            }
        }
        
        if ($blocked === false) {       
            $topThreeColors[] = $pallete->id;
        }
    };
    $lastID = $pallete->id;
    ?>
    <div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>)">
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=$pallete->id?>"></a>
    </div>
    <?php endforeach;sort($topThreeColors);?>
        
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search for similar colors images')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode('/',$topThreeColors)?>"><img src="<?=erLhcoreClassDesign::design('images/icons/color_wheel.png')?>" alt="" /></a>

</div>
<?php endif;?>
</div>
