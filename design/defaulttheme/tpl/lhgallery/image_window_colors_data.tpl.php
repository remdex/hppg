<?php $colorsDominant = erLhcoreClassModelGalleryPallete::getPictureDominantColors($image->pid,10); if (count($colorsDominant) > 0) : ?>
<div class="dominant-colors hide-full">
    <?php 
    $topThreeColors = array();
    $lastID = -1;   
    foreach (erLhcoreClassModelGalleryPallete::getPictureDominantColors($image->pid,10) as $pallete) : 
    if (count($topThreeColors) < 3 && ($lastID == -1 || abs($pallete->id - $lastID) > 10)) {
        
        $blocked = false;
        foreach ($topThreeColors as $color_id) { // Hard check to avoid appending similar collor to top three colors
            if (abs($pallete->id - $color_id) <= 10){
                $blocked = true;
            }
        }
        
        if ($blocked === false) {       
            $topThreeColors[] = $pallete->id;
        }
    };
    $lastID = $pallete->id;
    ?>
    <div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>)">
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=$pallete->id?>"></a>
    </div>
    <?php endforeach;sort($topThreeColors);?>
        
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Search for similar colors images')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=implode('/',$topThreeColors)?>"><img src="<?=erLhcoreClassDesign::design('images/icons/color_wheel.png')?>" alt="" /></a>

</div>
<?php endif;?>