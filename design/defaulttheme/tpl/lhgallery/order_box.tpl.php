<?php

$enableRelevance = (isset($enableRelevance) && $enableRelevance) == true ? true : false;

$sortNewMode = $enableRelevance == false ? '' : '/(sort)/new' ;
$matchMode = isset($matchMode) ? $matchMode : '';

$sortArrayAppend = array(
    'new'               => $sortNewMode,
    'newasc'            => '/(sort)/newasc',
    'popular'           => '/(sort)/popular',
    'popularasc'        => '/(sort)/popularasc',
    'lasthits'          => '/(sort)/lasthits',
    'lasthitsasc'       => '/(sort)/lasthitsasc',
    'toprated'          => '/(sort)/toprated',
    'topratedasc'       => '/(sort)/topratedasc',
    'lastrated'         => '/(sort)/lastrated',
    'lastratedasc'      => '/(sort)/lastratedasc',
    'lastcommented'     => '/(sort)/lastcommented',
    'lastcommentedasc'  => '/(sort)/lastcommentedasc',
    'relevance'         => '',
    'relevanceasc'      => '/(sort)/relevanceasc',
);

$resolutions = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'resolutions' );
$resolutionAppend = '';
if (isset($currentResolution) && key_exists($currentResolution,$resolutions)) {    
    $resolutionAppend = '/(resolution)/'.$resolutions[$currentResolution]['width'].'x'.$resolutions[$currentResolution]['height'];
}
$matchModeAppend = $matchMode == 'all' ? '/(match)/all' : '';
?>

<div class="right order-nav" id="sort-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
        <?php if ($enableRelevance == true) : ?>
            <li><a class="da<?=$mode == 'relevance' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort,$resolutionAppend,$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Relevance')?></a>
            <li class="sep"><a class="ar<?=$mode == 'relevanceasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/relevanceasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Relevance')?></a>
        <?php endif;?>
            <li><a class="da<?=$mode == 'new' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort,$resolutionAppend,$sortNewMode?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>
            <li class="sep"><a class="ar<?=$mode == 'newasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/newasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>
            <li><a class="da<?=$mode == 'popular' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popular<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a>
            <li class="sep"><a class="ar<?=$mode == 'popularasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popularasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a>
            <li><a class="da<?=$mode == 'lasthits' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthits<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a>
            <li class="sep"><a class="ar<?=$mode == 'lasthitsasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthitsasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a> 
            <li><a class="da<?=$mode == 'toprated' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/toprated<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>
            <li class="sep"><a class="ar<?=$mode == 'topratedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/topratedasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>
            <li><a class="da<?=$mode == 'lastrated' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastrated<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last rated')?></a>
            <li class="sep"><a class="ar<?=$mode == 'lastratedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastratedasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last rated')?></a>
            <li><a class="da<?=$mode == 'lastcommented' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommented<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a>
            <li><a class="ar<?=$mode == 'lastcommentedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommentedasc<?=$resolutionAppend?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a>
        </ul>    
</ul>
</div>

<div class="right order-nav" id="resolution-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <?php $currentResolution?>
            <li><a <?=$currentResolution == '' ? 'class="selor" ' : ''?> href="<?=$urlSortBase?><?echo $urlAppendSort?><?php echo $sortArrayAppend[$mode]?><?=$matchModeAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Any resolution')?></a>
            <?php foreach ($resolutions as $key => $resolution) : ?>
                <li><a <?=$currentResolution == $key ? 'class="selor" ' : ''?>href="<?=$urlSortBase?><?echo $urlAppendSort?><?php echo $sortArrayAppend[$mode]?>/(resolution)/<?=$resolution['width']?>x<?=$resolution['height']?><?=$matchModeAppend?>"><span><?=$resolution['width']?>x<?=$resolution['height']?></span></a>
            <?php endforeach;?>            
        </ul>    
</ul>
</div>

<?php if ($enableRelevance == true) : ?>
<div class="right order-nav" id="matchmode-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <li><a <?=($matchMode == '') ? 'class="selor" ' : ''?> href="<?=$urlSortBase?><?echo $urlAppendSort?><?php echo $sortArrayAppend[$mode]?><?=$resolutionAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Any keyword')?></a>
            <li><a <?=($matchMode == 'all') ? 'class="selor" ' : ''?>href="<?=$urlSortBase?><?echo $urlAppendSort?><?php echo $sortArrayAppend[$mode]?><?=$resolutionAppend?>/(match)/all"><span><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','All keywords')?></span></a>
        </ul>
</ul>
</div>
<?php endif;?>


<script> 
var _lactq = _lactq || [];
_lactq.push({'f':'hw_init_sort_box','a':['#sort-nav']});
_lactq.push({'f':'hw_init_sort_box','a':['#resolution-nav']});
_lactq.push({'f':'hw_init_sort_box','a':['#matchmode-nav']});
</script>