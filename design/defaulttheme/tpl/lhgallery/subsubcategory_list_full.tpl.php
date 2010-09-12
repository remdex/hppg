<div class="subcategory-list-full">
    <ul>
        <?php foreach ($subsubcategorys as $subcategory) : ?>    
                <li>
                <div class="right status-album btext"><span class="albums-category"><?=$subcategory->albums_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list_full','albums')?>, <?=$subcategory->images_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list_full','images')?></span></div>
                <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a></h3>
                
               <? if ($subcategory->description != '') : ?>
                <p><?=$subcategory->description?></p>
                <?endif;?>
                  
                <? 
                    $pages = new lhPaginator();
                    $pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('filter' => array('category' => $subcategory->cid)));
                    $pages->translationContext = 'gallery/album';
                    $pages->default_ipp = 8;
                    $pages->serverURL = $subcategory->path_url;
                    $pages->paginate();
                    if ($pages->items_total > 0) :
                ?>                
                <?php 
                $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $subcategory->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
                include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));endif;?>                   
                
        <?php endforeach;?>
    </ul>
</div>