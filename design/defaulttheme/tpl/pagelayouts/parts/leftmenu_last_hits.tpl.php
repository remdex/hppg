<div class="left-infobox">                    
    <h3><a href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last viewed images')?></a></h3>
    <?php 
    $cache = CSCacheAPC::getMem(); 
    $cacheVersion = $cache->getCacheVersion('last_hits_version',time(),600);
    if (($ResultCache = $cache->restore(md5($cacheVersion.'_lasthits_infobox'))) === false)
    {
        $items = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'sort' => 'mtime DESC, pid DESC','offset' => 0, 'limit' => 4));
        $appendImageMode = '/(mode)/lasthits';
        $ResultCache = '<ul class="last-hits-infobox">';                                                        
        foreach ($items as $item)
        {      
           $ResultCache .= '<li><a href="'.$item->url_path.$appendImageMode.'"><img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','View image').'" src="'.erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid).'" alt="'.htmlspecialchars($item->name_user).'" ></a>';
        }                            
        $ResultCache .= '</ul>';
        
        $cache->store(md5($cacheVersion.'_lasthits_infobox'),$ResultCache);
      
    }
    echo $ResultCache;
    ?>                                         							
</div>