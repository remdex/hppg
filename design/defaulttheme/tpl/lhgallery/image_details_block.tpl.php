<div class="picture-details">
    <div class="sub-header">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture details')?></h3>
    </div>
    <ul>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Filename')?>: <?=htmlspecialchars($image->filename);?></li>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','File size')?>: <?=$image->filesize_user;?></li>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Image rating')?><?=$image->votes > 0 ? ' ('.$image->votes.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','votes').')' : ''?>: <img src="<?php echo erLhcoreClassDesign::design('images/gallery/rating'.round($image->pic_rating/2000).'.gif');?>" alt=""/></li>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Date added')?>: <?=date('Y-m-d H:i:s',$image->ctime);?></li>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Dimensions')?>: <?=$image->pwidth?>x<?=$image->pheight?></li>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Displayed')?>: <?=$image->hits?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','times')?></li>
        <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','URL')?>: <a href="http://<?=$_SERVER['HTTP_HOST']?><?=$image->url_path?>">http://<?=$_SERVER['HTTP_HOST']?><?=$image->url_path?></a></li>
    </ul>
</div>