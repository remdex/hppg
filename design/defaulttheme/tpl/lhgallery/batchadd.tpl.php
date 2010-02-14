<? if (count($directoryList) > 0) :?>
<ul>
<?php 
foreach ($directoryList  as $directory) : ?>
<li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/batchadd/')?>?directory=<?=urlencode($directory);?>"><?=$directory?></a> | <a href="<?=erLhcoreClassDesign::baseurl('/gallery/batchadd/')?>?directory=<?=urlencode($directory);?>&import=1"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Import')?></a> | <a href="<?=erLhcoreClassDesign::baseurl('/gallery/batchadd/')?>?directory=<?=urlencode($directory);?>&importrecur=1"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Import recursive this directory')?></a></li>
<?endforeach;?>
</ul>
<br />
<?endif;?>

<?if (isset($writable) && $writable == false) : ?>
<p class="error">I cannot write this directory</p>
<?endif;?>

<? if (isset($filesList)) : ?>

<select id="AlbumID"><? 
$previousCategory='';
foreach (erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 5000,'offset' => 0,'sort' =>'category ASC')) as $album): ?>
    <?if ($previousCategory != $album->category): ?>
        <optgroup label="<? $previousCategory = $album->category;$pathReduced = $album->path_album;array_pop($pathReduced); foreach ($pathReduced as $pathItem){ echo $pathItem['title'].'/'; } ?>">
    <?endif;?>
    <option value="<?=$album->aid?>"><?=$album->title?></option>
    <?if ($previousCategory != $album->category): ?>
    </optgroup>
    <?endif;?>    
<?endforeach;?>
</select>


<table>
<? foreach ($filesList as $file) : 
if (!preg_match('/^(normal_|thumb_)/i',basename($file))) :
?>
    <tr>
        <td><?=$file?><img class="image_import" rel="<?=base64_encode($file);?>" src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>"/></td>
    </tr>
<?
endif;
endforeach;?>
</table>

<?if (isset($writable) && $writable == true) : ?>
<input type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Add images')?>" onclick="startImport()" />
<? else : ?>
<input type="button" disabled="disabled" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Add images')?>" />
<? endif;?>


<?endif;?>


<div id="status"></div>

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