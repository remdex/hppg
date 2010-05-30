<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Category edit');?> - <?php echo htmlspecialchars($category->name)?></legend> 

<div class="articlebody">

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>
		
	<form action="<?=erLhcoreClassDesign::baseurl('/gallery/editcategory/')?><?=$category->cid?>" method="post">

	<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/editcategory_form.tpl.php'));?> 
				
	<input type="submit" class="default-button" name="Update_Category" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Update');?>"/> &laquo; <a href="<?=erLhcoreClassDesign::baseurl('/gallery/admincategorys/')?><?=$category->parent?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','back')?></a>
					
	</form>
				

</div>
</fieldset>