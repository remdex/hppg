<div class="subcategory">
    <ul>
        <?php foreach ($subsubcategorys as $subcategory) : ?>    
                <li class="float-break">
                <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a><div class="right"><span class="albums-category"><?=$subcategory->albums_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list','albums')?>, <?=$subcategory->images_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list','images')?></span></div></h3>
                <? if ($subcategory->description != '') : ?>
                <p><?=$subcategory->description?></p>
                <?endif;?>
                
                </li> 
        <?php endforeach;?>
    </ul>
</div>