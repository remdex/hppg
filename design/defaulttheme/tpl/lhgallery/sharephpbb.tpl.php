<div style="width:400px;height:300px;">
    <div>
    	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/sharephpbb','Link to image')?></h3>
    	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars('[url=http://'.$_SERVER['HTTP_HOST'].$Image->url_path.']'.$Image->name_user.'[/url]');?></textarea>	
    </div>
    <div>
    	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/sharephpbb','Link to image with thumbnail')?></h3>
    	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars("[url=http://{$_SERVER['HTTP_HOST']}{$Image->url_path}][img]http://{$_SERVER['HTTP_HOST']}".erLhcoreClassDesign::imagePath($Image->filepath.'thumb_'.urlencode($Image->filename))."[/img][/url]");?></textarea>	
    </div>
    <div>
    	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/sharephpbb','Link to image with medium size thumbnail')?></h3>
    	<textarea onclick="$(this).select()" class="default-textarea" style="width:95%;font-size:11px;height:75px;"><?php echo htmlspecialchars("[url=http://{$_SERVER['HTTP_HOST']}{$Image->url_path}][img]http://{$_SERVER['HTTP_HOST']}".erLhcoreClassDesign::imagePath($Image->filepath.'normal_'.urlencode($Image->filename))."[/img][/url]");?></textarea>	
    </div>
</div>