<div class="header-list">
<h1>Edit an album</h1>
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
<br />

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/gallery/editalbum/')?><?=$album->aid?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Album name');?> *</label>
<input class="inputfield" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Description');?></label>
<textarea name="AlbumDescription" class="default-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Keywords');?></label>
<input class="inputfield" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>

<input type="submit" class="default-button" name="CreateAlbum" value="Update"/>

</form>

