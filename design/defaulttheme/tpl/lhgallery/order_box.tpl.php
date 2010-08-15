<div class="right order-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">
            <li><a class="da<?=$mode == 'newdesc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a></li>
            <li class="sep"><a class="ar<?=$mode == 'newasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/newasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>    </li>
            <li><a class="da<?=$mode == 'popular' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popular"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a></li>
            <li class="sep"><a class="ar<?=$mode == 'popularasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popularasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a></li>
            <li><a class="da<?=$mode == 'lasthits' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthits"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a></li>
            <li class="sep"><a class="ar<?=$mode == 'lasthitsasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthitsasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a>   </li> 
            <li><a class="da<?=$mode == 'toprated' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/toprated"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a></li>
            <li class="sep"><a class="ar<?=$mode == 'topratedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/topratedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>    </li>
            <li><a class="da<?=$mode == 'lastcommented' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommented"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a></li>
            <li><a class="ar<?=$mode == 'lastcommentedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommentedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a></li>
        </ul>
    </li>
</ul>
</div>

<script type="text/javascript">
hw.initSortBox();
</script>