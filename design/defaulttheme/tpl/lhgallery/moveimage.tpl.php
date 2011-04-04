<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/addimages','Move image to another album')?> - <?=htmlspecialchars($image->name_user)?></h1>
</div>

<?php if (isset($assigned)) : ?>
<script>
parent.location.reload(true);
parent.$.colorbox.close();
</script>
<?php endif;?>

<div class="articlebody">		
	<form action="" method="post">
	<input type="text" class="default-input" id="album_suggest_name">
	<input type="button" class="default-button" id="findButton" value="Find album">
	<div id="album-suggest"></div>	
	<input type="submit" class="default-button" name="MoveImages" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Update');?>"/>					
	</form>
</div>

<script>
$('#findButton').click(function(){	
	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumnamesuggest')?>/0/"+escape($('#album_suggest_name').val()), {} , function(data){	
                   $('#album-suggest').html(data.result);
    });	
});
</script>