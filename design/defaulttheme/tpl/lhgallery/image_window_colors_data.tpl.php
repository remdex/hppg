<?php $colorsDominant = erLhcoreClassModelGalleryPallete::getPictureDominantColors($image->pid,10); if (count($colorsDominant) > 0) : ?>
<div class="dominant-colors hide-full">
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
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=$pallete->id?>"></a>
    </div>
    <?php endforeach;sort($topThreeColors);?>
        
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search for similar colors images')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode('/',$topThreeColors)?>"><img src="<?=erLhcoreClassDesign::design('images/icons/color_wheel.png')?>" alt="" /></a>

    <?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'enabled' ) === true) : ?>
    &nbsp;<a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search for visualy similar images')?>" href="<?=erLhcoreClassDesign::baseurl('similar/image')?>/<?=$image->pid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/eye.png')?>" alt="" /></a>
    <?php endif;?>
    
</div>
<?php endif;?>