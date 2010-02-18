<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Category edit');?> - <? echo htmlspecialchars($category->name)?></legend> 

<div class="articlebody">

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>


	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('/gallery/editcategory/')?><?=$category->cid?>" method="post">
			<table>				
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Name');?></td>
					<td><input type="text" class="inputfield" name="CategoryName" value="<?=htmlspecialchars($category->name)?>"/></td>
				</tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Description');?></td>
					<td><textarea name="DescriptionCategory" class="textarestyle"><?=htmlspecialchars($category->description)?></textarea></td>
				</tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Owner');?></td>
					<td>
					   <a href="<?=erLhcoreClassDesign::baseurl('/user/edit/')?><?=$category->owner_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Owner');?></a>
					</td>
				</tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Hide subcategorys in frontpage');?></td>
					<td>
					   <input name="HideFrontpage" type="checkbox" value="on" <?=$category->hide_frontpage == 1 ? 'checked="checked"' : ''?> />
					</td>
				</tr>												
				<tr>
					<td></td>
					<td><input type="submit" class="default-button" name="Update_Category" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Update');?>"/> &laquo; <a href="<?=erLhcoreClassDesign::baseurl('/gallery/admincategorys/')?><?=$category->parent?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','back')?></a></td>
				</tr>
			</table>		
		</form>
	</div>
</div>
<br />
</fieldset>
