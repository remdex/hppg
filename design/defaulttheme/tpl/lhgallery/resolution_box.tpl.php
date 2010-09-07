<?php
    $resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );
    
    $resolutionAppend = '';
    
    if (isset($currentResolution) && key_exists($currentResolution,$resolutions)) {    
        $resolutionAppend = '/(resolution)/'.$resolutions[$currentResolution]['width'].'x'.$resolutions[$currentResolution]['height'];
    }
?>

<div class="right order-nav" id="resolution-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <?php $currentResolution?>
            <li><a <?=$currentResolution == '' ? 'class="selor" ' : ''?> href="<?=$urlSortBase?>">Any resolution</a></li>
            <?php foreach ($resolutions as $key => $resolution) : ?>
                <li><a <?=$currentResolution == $key ? 'class="selor" ' : ''?>href="<?=$urlSortBase?>/(resolution)/<?=$resolution['width']?>x<?=$resolution['height']?>"><span><?=$resolution['width']?>x<?=$resolution['height']?><span></a></li>
            <?php endforeach;?>            
        </ul>
    </li>
</ul>
</div>

<script type="text/javascript">
hw.initSortBox('#resolution-nav');
</script>