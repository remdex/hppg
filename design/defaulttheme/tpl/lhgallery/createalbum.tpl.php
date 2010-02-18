<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbum','New album')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/gallery/createalbum/')?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbum','Album name');?> *</label>
<input class="inputfield" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbum','Description');?></label>
<textarea name="AlbumDescription" class="default-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbum','Keywords');?></label>
<input class="inputfield" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>

<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbum','Create')?>"/> <input type="submit" class="default-button" name="CreateAlbumAndUpload" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbum','Create and upload photos')?> &raquo;"/>

</form>

