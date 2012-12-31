<div style="width:480px;height:340px;">
    <div>
    	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/sharehtml','Link to image')?></h3>
    	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars('<a href="http://'.$_SERVER['HTTP_HOST'].$Image->url_path.'">'.$Image->name_user.'</a>');?></textarea>	
    </div>
    <div>
    	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/sharehtml','Link to image with thumbnail')?></h3>
    	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars("<a href=\"http://{$_SERVER['HTTP_HOST']}{$Image->url_path}\"><img title=\"$Image->name_user\" src=\"http://{$_SERVER['HTTP_HOST']}".erLhcoreClassDesign::imagePath($Image->filepath.'thumb_'.urlencode($Image->filename))."\" alt=\"".htmlspecialchars($Image->name_user)."\" /></a>");?></textarea>	
    </div>
    <div>
    	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/sharehtml','Link to image with medium size thumbnail')?></h3>
    	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars("<a href=\"http://{$_SERVER['HTTP_HOST']}{$Image->url_path}\"><img title=\"$Image->name_user\" src=\"http://{$_SERVER['HTTP_HOST']}".erLhcoreClassDesign::imagePath($Image->filepath.'normal_'.urlencode($Image->filename))."\" alt=\"".htmlspecialchars($Image->name_user)."\" /></a>");?></textarea>	
    </div>
</div>