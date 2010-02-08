<div class="subcategory-list-full">
    <ul>
        <?php foreach ($subcategorys as $subcategory) :         
        $albumCount = $subcategory->albums_count;        
        if ($albumCount > 0):
        ?>    
                <li>
                <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a><div class="right status-album"><span class="albums-category"><?=$albumCount;?> albums, <?=$subcategory->images_count;?> images</span></div></h3>
               <? if ($subcategory->description != '') : ?>
                <p><?=$subcategory->description?></p>
                <?endif;?>
                  
                <? 
                    $pages = new lhPaginator();
                    $pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true, 'filter' => array('category' => $subcategory->cid)));
                    $pages->translationContext = 'gallery/album';
                    $pages->default_ipp = 8;
                    $pages->serverURL = $subcategory->path_url;
                    $pages->paginate();
                    if ($pages->items_total > 0) :
                ?>                
                <?php 
                $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $subcategory->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
                include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));endif;?>
  
                
                <? $subsubcategorys = erLhcoreClassModelGalleryCategory::getParentCategories($subcategory->cid);
                if (count($subsubcategorys) > 0) : ?>
                 <?php include(erLhcoreClassDesign::designtpl('lhgallery/subsubcategory_list.tpl.php'));?> 
                <?endif;?>
                
                
                </li> 
        <?php endif;endforeach;?>
    </ul>
</div>