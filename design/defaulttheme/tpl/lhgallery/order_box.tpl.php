<?php

$sortArrayAppend = array(
    'newdesc'           => '',
    'newasc'            => '/(sort)/newasc',
    'popular'           => '/(sort)/popular',
    'popularasc'        => '/(sort)/popularasc',
    'lasthits'          => '/(sort)/lasthits',
    'lasthitsasc'       => '/(sort)/lasthitsasc',
    'toprated'          => '/(sort)/toprated',
    'topratedasc'       => '/(sort)/topratedasc',
    'lastcommented'     => '/(sort)/lastcommented',
    'lastcommentedasc'  => '/(sort)/lastcommentedasc',
);

$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );

$resolutionAppend = '';

if (isset($currentResolution) && key_exists($currentResolution,$resolutions)) {    
    $resolutionAppend = '/(resolution)/'.$resolutions[$currentResolution]['width'].'x'.$resolutions[$currentResolution]['height'];
}

?>

<div class="right order-nav" id="sort-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <li><a class="da<?=$mode == 'newdesc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort,$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>
            <li class="sep"><a class="ar<?=$mode == 'newasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/newasc<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>
            <li><a class="da<?=$mode == 'popular' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popular<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a>
            <li class="sep"><a class="ar<?=$mode == 'popularasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popularasc<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a>
            <li><a class="da<?=$mode == 'lasthits' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthits<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a>
            <li class="sep"><a class="ar<?=$mode == 'lasthitsasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthitsasc<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a> 
            <li><a class="da<?=$mode == 'toprated' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/toprated<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>
            <li class="sep"><a class="ar<?=$mode == 'topratedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/topratedasc<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>
            <li><a class="da<?=$mode == 'lastcommented' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommented<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a>
            <li><a class="ar<?=$mode == 'lastcommentedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommentedasc<?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a>
        </ul>    
</ul>
</div>

<div class="right order-nav" id="resolution-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <?php $currentResolution?>
            <li><a <?=$currentResolution == '' ? 'class="selor" ' : ''?> href="<?=$urlSortBase?><?echo $urlAppendSort?><?php echo $sortArrayAppend[$mode]?>">Any resolution</a>
            <?php foreach ($resolutions as $key => $resolution) : ?>
                <li><a <?=$currentResolution == $key ? 'class="selor" ' : ''?>href="<?=$urlSortBase?><?echo $urlAppendSort?><?php echo $sortArrayAppend[$mode]?>/(resolution)/<?=$resolution['width']?>x<?=$resolution['height']?>"><span><?=$resolution['width']?>x<?=$resolution['height']?></span></a>
            <?php endforeach;?>            
        </ul>    
</ul>
</div>

<script>
hw.initSortBox('#sort-nav');
hw.initSortBox('#resolution-nav');
</script>