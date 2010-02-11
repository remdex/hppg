<ul>
<?php 
foreach ($directoryList  as $directory) : ?>
<li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/batchadd/')?>?directory=<?=urlencode($directory);?>"><?=$directory?></a> | <a href="<?=erLhcoreClassDesign::baseurl('/gallery/batchadd/')?>?directory=<?=urlencode($directory);?>&import=1">Import</a></li>
<?endforeach;?>
</ul>


<select id="AlbumID">
<? foreach (erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 5000,'offset' => 0,'sort' =>'title ASC')) as $album): ?>
    <option value="<?=$album->aid?>"><?    
    foreach ($album->path_album as $pathItem) {
        echo $pathItem['title'].'/';
    }
    ?></option>
<?endforeach;?>
</select>
<table>
<? foreach ($filesList as $file) : 
if (!preg_match('/^(normal_|thumb_)/i',basename($file))) :
?>
    <tr>
        <td><?=$file?><img class="image_import" rel="<?=base64_encode($file);?>" src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>"/></td>
    </tr>
<?
endif;
endforeach;?>
</table>
<input type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Add images')?>" onclick="startImport()" />
<div id="status">

</div>

<script type="text/javascript">
function startImport()
{
    if ($('.image_import').eq(0).attr('rel') != undefined)
    {
        $.getJSON("<?=erLhcoreClassDesign::baseurl('/gallery/addimagesbatch/')?>"+$('#AlbumID').val()+"/?image="+$('.image_import').eq(0).attr('rel'), {} , function(data){	
              $('.image_import').eq(0).attr('src','/design/defaulttheme/images/icons/accept.png');
              $('.image_import').eq(0).removeClass('image_import');	
    		   startImport();        
    	});    	
    }  

}
</script>