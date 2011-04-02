<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Replace image');?></h1></div>

<?php if (isset($image_replace)) : ?>

<div id="image-new-data" style="display:none">
<?php $item = $image ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?> 
</div>

<script>
parent.$('#pid_thumb_<?=$image->pid?> > a').html($('#image-new-data').html());
parent.$.colorbox.close();
</script>

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