<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','New category');?></legend> 

<div class="articlebody">

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>


	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('/gallery/createcategory/')?><?=isset($category_parent) ? $category_parent->cid : ''?>" method="post">
			<table>				
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Name');?></td>
					<td><input type="text" class="inputfield" name="CategoryName" value="<?=htmlspecialchars($category->name)?>"/></td>
				</tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Description');?></td>
					<td><textarea name="DescriptionCategory" class="textarestyle"><?=htmlspecialchars($category->description)?></textarea></td>
				</tr>				
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Hide subcategorys in frontpage');?></td>
					<td>
					   <input name="HideFrontpage" type="checkbox" value="on" <?=$category->hide_frontpage == 1 ? 'checked="checked"' : ''?> />
					</td>
				</tr>												
				<tr>
					<td></td>
					<td><input type="submit" class="default-button" name="Update_Category" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Save');?>"/> 
					
					<?php if (isset($category_parent)) : ?>			
					&laquo; <a href="<?=erLhcoreClassDesign::baseurl('/gallery/admincategorys/')?><?=$category_parent->cid?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','back')?></a>
					<?endif;?>
					</td>
				</tr>
			</table>		
		</form>
	</div>
</div>
<br />
</fieldset>
