<table>
<tr>
	<td>
		<div class="in-blk">
			<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album name');?> *</label>
			<input class="default-input" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
		</div>
	</td>
	<td>
		<div class="in-blk">
			<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album category');?> *</label>
			<select name="AlbumCategoryID" class="default-select">
				<?php foreach (erLhcoreClassModelGalleryCategory::getParentCategories(array('disable_sql_cache' => true,'use_iterator' => true,'limit' => 1000000)) as $category) : ?>
					<option value="<?=$category->cid?>" <?=$category->cid == $album->category ? 'selected="selected"' : ''?>><?=htmlspecialchars($category->name)?></option>
				<?php endforeach;?>
			</select>
		</div>
	</td>
	<td>
		<div class="in-blk">
			<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album owner');?> *</label>			
			<select name="UserID" class="default-select">
				<?php foreach (erLhcoreClassUser::getUserList() as $user) : ?>
					<option value="<?=$user['id']?>" <?=$user['id'] == $album->owner_id ? 'selected="selected"' : ''?>><?=htmlspecialchars($user['username'])?></option>
				<?php endforeach;?>
			</select>				
		</div>
	</td>
</tr>
</table>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Description');?></label>
<textarea name="AlbumDescription" class="default-textarea big-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Keywords');?></label>
<input class="default-input" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Public');?></label>
<input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','All users can uploads images to this album');?>" value="on" <?=$album->public == 1 ? 'checked="checked"' : ''?> name="AlbumPublic" />
</div>