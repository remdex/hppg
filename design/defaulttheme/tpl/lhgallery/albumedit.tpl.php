<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album edit')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('gallery/albumedit')?>/<?=$album->aid?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album name');?> *</label>
<input class="inputfield" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
</div>

<?php $bbcodeElementID = '#IDAlbumDescription';?>
<?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Description');?></label>
<textarea name="AlbumDescription" rows="20" id="IDAlbumDescription" class="default-textarea big-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Keywords');?></label>
<input class="inputfield" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>


<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Hidden');?></label>
<input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Hide album from users');?>" value="on" <?=$album->hidden == 1 ? 'checked="checked"' : ''?> name="AlbumHidden" />
</div>


<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album thumbnail');?></label>
<?php if ($album->album_pid == 0) : ?>
    <?php if ($album->album_thumb_path !== false) :?> 
    <img src="<?=erLhcoreClassDesign::imagePath($album->album_thumb_path)?>" alt="" width="130" height="140">
    <?php else :?>
    <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140">            
    <?php endif;?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Newest album image');?>.
<?php else : ?>
    <?php if ($album->album_thumb_path !== false) :?> 
    <img src="<?=erLhcoreClassDesign::imagePath($album->album_thumb_path)?>" alt="" width="130" height="140">
    <?php else :?>
    <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140">            
    <?php endif;?>Assigned image. <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Revert to newest album image display mode')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/albumedit')?>/<?=$album->aid?>/(action)/removethumb"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Remove');?></a>
<?php endif;?>
</div>



<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Update')?>"/>

</form>

