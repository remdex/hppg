<div class="float-break control-block">

<div class="left">
<a class="ad-fv" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Add to favorites')?>"></a>

<?php if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>

    <a class="ad-html" href="<?=erLhcoreClassDesign::baseurl('gallery/sharehtml')?>/<?=$image->pid;?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Share this page HTML code')?>"></a>
    <a class="ad-phpbb" href="<?=erLhcoreClassDesign::baseurl('gallery/sharephpbb')?>/<?=$image->pid;?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Share PHPBB code')?>"></a>

    <?php if ($image->anaglyph == 1) : ?>
    <a class="ad-anaglyph" href="<?=erLhcoreClassDesign::baseurl('gallery/anaglyph')?>/<?=$image->pid;?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Anaglyph version')?>" ></a>
    <?php endif;?>

<?php endif;?>
</div>

<div class="right"><div class="act-blc" id="tags-container"><input type="text" class="inputfield" id="IDtagsPhoto" value="" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Enter tags separated by space')?>" /><input type="button" onclick="hw.tagphoto(<?=$image->pid;?>)" class="default-button" value="tag it!" /><i>Tag this photo</i></div></div>

</div>