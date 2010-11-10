<div class="subcategory-list-full">
   
        <?php foreach ($subcategorys as $subcategory) :         
        $albumCount = $subcategory->albums_count;        
        
        ?>    

                <div class="sub-category-content">
        
                <div class="right status-album"><span class="albums-category"><?=$albumCount;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list_full','albums')?>, <?=$subcategory->images_count;?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list_full','images')?></span></div>
                
                <div class="sub-header">
                <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a></h3>
                </div>
                
               <? if ($subcategory->description != '') : ?>
                <p><?=$subcategory->description?></p>
                <?endif;?>
                  
                <? 
                    $pages = new lhPaginator();
                    $pages->current_page = 1;
                    $pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true, 'filter' => array('category' => $subcategory->cid)));
                    $pages->setItemsPerPage(8);
                    $pages->serverURL = $subcategory->path_url;
                    $pages->paginate();
                    if ($pages->items_total > 0) :
                ?>                
                <?php 

                $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $subcategory->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page)); 
                
                ?>
               
               <?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php')); ?>
                
               <?php endif;?>  
                
                <? 
                $cache = CSCacheAPC::getMem();
                $subsubcategorys = erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => $subcategory->cid),'cache_key' => 'version_'.$cache->getCacheVersion('category_'.$subcategory->cid)));
                if (count($subsubcategorys) > 0) : ?>
                 <?php include(erLhcoreClassDesign::designtpl('lhgallery/subsubcategory_list.tpl.php'));?> 
                <?endif;?>
                </div>  
                      
        <?php endforeach;?>
  
</div>