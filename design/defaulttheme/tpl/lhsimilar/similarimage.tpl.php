
<?php if ($image !== false) : ?>
    <div class="header-list">
                <h1>Visualy similar images to - <?=htmlspecialchars($image->name_user)?></h1>
    </div>
          
    <div class="img float-break">
    <?php if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>
    
        <a rel="<?=$image->pwidth?>" href="<?=$image->url_path?>"><img class="main" src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Click to see fullsize')?>" ></a>
    
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
    		<p>Texte alternatif</p>
    	</object>
    	
    <?php endif;?>
    </div>    

    
<div class="right" style="width:300px;">
    <div id="ad-image-upload">
    	<noscript>
    		<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/orderitemdetails/orderitemdetails_display_ad_magazine','Please enable JavaScript to upload image');?></p>
    	</noscript>
    </div>
    
    <ul id="listElement-ad-image">
    	
    </ul>
</div>

<?php else :?>

    <div class="header-list">
                <h1>Search for visualy similar images</h1>
    </div>
    
    <div id="ad-image-upload">
    	<noscript>
    		<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/orderitemdetails/orderitemdetails_display_ad_magazine','Please enable JavaScript to upload image');?></p>
    	</noscript>
    </div>
    
    <ul id="listElement-ad-image">
    	
    </ul>
<?php endif;?>


<script type="text/javascript">
var uploader = new qq.FileUploader({
    element: document.getElementById('ad-image-upload'),
    listElement: document.getElementById('listElement-ad-image'),
    action: '<?=erLhcoreClassDesign::baseurl('similar/uploadsimilar')?>',
    allowedExtensions:[<?=erLhcoreClassModelSystemConfig::fetch('allowed_file_types')->current_value;?>],
    autoStart : true,
    sizeLimit : <?=(int)(erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024)?>,
    maxFiles : <?=(int)(erLhcoreClassModelSystemConfig::fetch('file_upload_limit')->current_value)?>,   
    onComplete: function(id, fileName, responseJSON) {
        if (responseJSON.success == 'true') {   
            var strintID = String(id);       
            $('#file_id_row_'+strintID.replace('qq-upload-handler-iframe','')).fadeOut();
            $('#similar-images-container').removeClass('ajax-loading-items');
            $('#similar-images-container .img-list').html(responseJSON.result);
        }
    },
    onStart : function(){ 
        $('.qq-upload-spinner').addClass('active-spinner');
        $('#similar-images-container').addClass('ajax-loading-items');
        $('#similar-images-container .img-list').html('');
        return true;
    },
    onSubmit : function(){ 
        $('#similar-images-container').addClass('ajax-loading-items');
        $('#similar-images-container .img-list').html('');
        return true;
    },
	template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                '<div class="qq-upload-button"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Click to choose image, you can also Drag and Drop file here (Drag and drop only for FF, Chrome).')?></div>' +
                '<ul class="qq-upload-list"></ul>' + 
             '</div>',
    fileTemplate: '<li class="float-break" id="file_id_row_{file_id}"><span class="qq-upload-file"></span>' +
                '<span class="qq-upload-spinner"></span>' +
                '<span class="qq-upload-size"></span>' +
                '<a class="qq-upload-cancel" href="#"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/uploadadorderitem_content','Cancel');?></a>' +
                '<span class="qq-upload-failed-text"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/uploadadorderitem_content','Failed');?></span>' +
                '</li>',
    multiple: false,
    debug: true
});   
</script>

<div id="similar-images-container" style="clear:both;padding-top:10px;">
    <div class="float-break img-list">
    <?php if ($image !== false) : ?>
    <? 
    $counter = 1;
    foreach ($items as $key => $item) : 
    ?>
        <div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
            <div class="thumb-pic">
                <a class="inf-img" rel="<?=$item->pid?>"></a>
                <a href="<?=erLhcoreClassDesign::baseurl('similar/image')?>/<?=$item->pid?>">            
                <?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?>            
                </a>           
            </div>
            <div class="thumb-attr">
            
            <div class="tit-item">
                <h3><a title="<?=htmlspecialchars($item->name_user);?>" href="<?=erLhcoreClassDesign::baseurl('similar/image')?>/<?=$item->pid?>">
                    <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
                    </a>
                </h3>
            </div>
            
            <span class="res-ico">
            <?=$item->pwidth?>x<?=$item->pheight?>
            </span>    
            
            <span class="hits-ico">
            <?=$item->hits?>
            </span>               
            
            </div>
        </div>   
    <?$counter++;endforeach; ?> 
    
    <script>
    $('.thumb-attr a').each(function(index) {	
    	$(this).attr('href',$(this).attr('rel'));
    });
    hw.initInfoWindow('');
    </script>
 
    <?php endif;?>
    </div>
</div>