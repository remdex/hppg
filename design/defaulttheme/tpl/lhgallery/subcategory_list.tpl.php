<div class="subcategory">
    <ul>
        <?php foreach ($subcategorys as $subcategory) : ?>    
                <li class="float-break">
                <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a><div class="right"><span class="albums-category"><?=$subcategory->albums_count;?> albums, <?=$subcategory->images_count;?> images</span></div></h3>
                <? if ($subcategory->description != '') : ?>
                <p><?=$subcategory->description?></p>
                <?endif;?>
                
                </li> 
        <?php endforeach;?>
    </ul>
</div>