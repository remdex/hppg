<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','New album')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/gallery/createalbumadmin/')?><?=$categoryID?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Album name');?> *</label>
<input class="inputfield" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Description');?></label>
<textarea name="AlbumDescription" class="default-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Keywords');?></label>
<input class="inputfield" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Public');?></label>
<input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','All users can uploads images to this album');?>" value="on" <?=$album->public == 1 ? 'checked="checked"' : ''?> name="AlbumPublic" />
</div>

<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Save')?>"/>

</form>

