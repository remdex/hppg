<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Public images archive upload')?></a></h1>
</div>
<? if (isset($errArr)) : ?>
<div class="error-list">
<br />

<ul>
<?php foreach ($errArr as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<? endif;?>

<div id="ad-image-upload">
	<noscript>
		<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/orderitemdetails/orderitemdetails_display_ad_magazine','Please enable JavaScript to upload image');?></p>
	</noscript>
</div>

<ul id="listElement-ad-image">
	
</ul>

<input type="button" class="default-button" id="ConvertButton" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Upload')?>" />

<script type="text/javascript">
var template_uploader = '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                '<div class="qq-upload-button"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Click to choose zip to upload, you can also drop files here (Darg and drop only for FF, Chrome).')?></div>' +
                '<ul class="qq-upload-list"></ul>' + 
             '</div>';
                     
var file_template = '<li class="float-break" idattr="{file_id}" id="file_id_row_{file_id}"><span class="qq-upload-file"></span>' +
                '<span class="qq-upload-spinner"></span>' +
                '<span class="qq-upload-size"></span>' +
                '<a class="qq-upload-cancel" href="#"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/uploadadorderitem_content','Cancel');?></a>' +
                '<span class="qq-upload-failed-text"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/uploadadorderitem_content','Failed');?></span>' +
                '<div class="right"><div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Enter new album name or choose public album')?></div>'+				
				'<input type="text" id="PhotoTitle{file_id}" value="" class="inputfield" />'+
				'<select id="AlbumIDToUploadArchive{file_id}"><option value="">--Choose--</option><?php foreach ($items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('public' => 1),'offset' => 0, 'limit' => 100)) as $album) : ?><option value="<?=$album->aid?>"><?=$album->title?></option><?php endforeach;?></select>'+
				'<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Keywords')?></div>'+	
				'<input type="text" id="PhotoKeyword{file_id}" value="" class="inputfield" />'+	
				'<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','description')?></div>'+			
				'<textarea class="default-textarea" id="PhotoDescription{file_id}"></textarea>' +
				'</div></li>';
                     
var _lactq = _lactq || [];
_lactq.push({'f':'hw_init_public_upload_zip','a':[<?=(int)(erLhcoreClassModelSystemConfig::fetch('max_archive_size')->current_value*1024)?>,<?=(int)(erLhcoreClassModelSystemConfig::fetch('file_upload_limit')->current_value)?>,template_uploader,file_template,'<?=erLhcoreClassDesign::baseurl('gallery/uploadarchive')?>']});
</script>

