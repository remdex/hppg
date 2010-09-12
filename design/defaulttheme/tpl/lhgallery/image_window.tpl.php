<div class="float-break">
<div class="img">
<a id="photo_full" href="<?=erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename))?>"><img src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Click to see fullsize')?>" ></a>
<?php if( $image->caption != '') : ?>
<div class="float-break cap-img"><?=nl2br(htmlspecialchars($image->caption))?></div>
<?endif;?>
</div>
</div>