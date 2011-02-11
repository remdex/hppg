<div id="imageInfoWindow">
<ul>  
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Filename')?>:</strong> <?=htmlspecialchars($image->filename);?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','File size')?>:</strong> <?=$image->filesize_user;?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Image rating')?></strong><?=$image->votes > 0 ? ' ('.$image->votes.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','votes').')' : ''?>: <img src="<?php echo erLhcoreClassDesign::design('images/gallery/rating'.round($image->pic_rating/2000).'.gif');?>" alt="">
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Date added')?>:</strong> <?=date('Y-m-d H:i:s',$image->ctime);?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Dimensions')?>:</strong> <?=$image->pwidth?>x<?=$image->pheight?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Displayed')?>:</strong> <?=$image->hits?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','times')?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Album')?>:</strong> <a href="<?=$image->album_path?>"><?=$image->album_title?></a>    
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Owner')?>:</strong> <a href="<?=erLhcoreClassDesign::baseurl('gallery/ownercategorys')?>/<?=$image->owner_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','More user images')?></a>
</ul>
</div>