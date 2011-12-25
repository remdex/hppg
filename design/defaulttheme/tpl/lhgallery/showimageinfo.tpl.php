<div id="imageInfoWindow">
<ul>  
    <li><h3><a title="View image" href="<?=$image->url_path.$sort?>"><?=htmlspecialchars($image->name_user);?></a></h3>
    <li>
    <?php $colorsDominant = erLhcoreClassModelGalleryPallete::getPictureDominantColors($image->pid,10); if (count($colorsDominant) > 0) : ?>
    <div class="dominant-colors dom-colors-info">
        <?php 
        $topThreeColors = array();
        $lastID = -1;   
        $max = 15400/2;
        $min = 1;
        $rmax = 3;
        $rmin = 1;
        $topThreeColorsCounter = 0;
        
        foreach ($colorsDominant as $pallete) : 
        if ($topThreeColorsCounter < 4 && ($lastID == -1 || abs($pallete->id - $lastID) > 10)) {
            
            $blocked = false;
            foreach ($topThreeColors as $color_id) { // Hard check to avoid appending similar collor to top three colors
                if (abs($pallete->id - $color_id) <= 10) {
                    $blocked = true;
                }
            }
            
            if ($blocked === false) { 
                $repeatPallete = ceil(((3*($pallete->matches))/($max-$min)));
                for ( $i = 1; $i <= $repeatPallete; $i++ ) {
                    $topThreeColors[] = $pallete->id;
                }
                $topThreeColorsCounter++;
            }
        };
        $lastID = $pallete->id;
        ?>
        <div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>)">
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?><?=(strpos($sort,'/(color)/') !== false ? str_replace('/(color)/','/(color)/'.$pallete->id.'/',$sort) : $sort.'/(color)/'.$pallete->id)?>"></a>
        </div>
        <?php endforeach;sort($topThreeColors);?>
        
    </div>
    <?php endif;?>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search for similar colors images')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/search')?><?=(strpos($sort,'/(color)/') !== false ? str_replace('/(color)/','/(color)/'.implode('/',$topThreeColors).'/',$sort) : $sort.'/(color)/'.implode('/',$topThreeColors))?>"><img src="<?=erLhcoreClassDesign::design('images/icons/color_wheel.png')?>" alt="" /></a>
    <?php if (erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'enabled' ) === true) : ?>
    &nbsp;<a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search for visualy similar images')?>" href="<?=erLhcoreClassDesign::baseurl('similar/image')?>/<?=$image->pid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/eye.png')?>" alt="" /></a>
    <?php endif;?>
        
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','File size')?>:</strong> <?=$image->filesize_user;?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Image rating')?></strong><?=$image->votes > 0 ? ' ('.$image->votes.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','votes').')' : ''?>: <img src="<?php echo erLhcoreClassDesign::design('images/gallery/rating'.round($image->pic_rating/2000).'.gif');?>" alt="">
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Date added')?>:</strong> <?=date('Y-m-d H:i:s',$image->ctime);?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Dimensions')?>:</strong> <?=$image->pwidth?>x<?=$image->pheight?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Displayed')?>:</strong> <?=$image->hits?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','times')?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Album')?>:</strong> <a href="<?=$image->album_path?>"><?=$image->album_title?></a>    
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Owner')?>:</strong> <a href="<?=erLhcoreClassDesign::baseurl('user/profile')?>/<?=$image->owner_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','More user images')?></a>
</ul>
</div>