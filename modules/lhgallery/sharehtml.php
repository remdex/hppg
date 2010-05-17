<?php

try {
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}
?>

<div style="width:400px;height:300px;">
<div>
	<h3>Link to image</h3>
	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars('<a href="http://'.$_SERVER['HTTP_HOST'].$Image->url_path.'">'.$Image->name_user.'</a>');?></textarea>	
</div>
<div>
	<h3>Link to image with thumbnail</h3>
	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars("<a href=\"http://{$_SERVER['HTTP_HOST']}{$Image->url_path}\"><img title=\"$Image->name_user\" src=\"http://{$_SERVER['HTTP_HOST']}".erLhcoreClassDesign::imagePath($Image->filepath.'thumb_'.urlencode($Image->filename))."\" alt=\"".htmlspecialchars($Image->name_user)."\" /></a>");?></textarea>	
</div>
<div>
	<h3>Link to image with medium size thumbnail</h3>
	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars("<a href=\"http://{$_SERVER['HTTP_HOST']}{$Image->url_path}\"><img title=\"$Image->name_user\" src=\"http://{$_SERVER['HTTP_HOST']}".erLhcoreClassDesign::imagePath($Image->filepath.'normal_'.urlencode($Image->filename))."\" alt=\"".htmlspecialchars($Image->name_user)."\" /></a>");?></textarea>	
</div>

 
</div>

<?php exit;?>
