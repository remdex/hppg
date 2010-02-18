<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Edit an album')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/gallery/albumeditadmin/')?><?=$album->aid?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album name');?> *</label>
<input class="inputfield" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Description');?></label>
<textarea name="AlbumDescription" class="default-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Keywords');?></label>
<input class="inputfield" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>

<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Update')?>"/>

</form>

