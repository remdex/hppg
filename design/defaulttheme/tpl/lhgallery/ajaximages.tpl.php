<? 
if (count($imagesAjax) > 0) :
$counter = 1;
foreach ($imagesAjax as $key => $item) : ?>
<div class="image-thumb">
        <div class="thumb-pic">
            <a rel="<?=$item->pid?>" href="<?=$item->url_path?><?=isset($urlAppend) ? $urlAppend : ''?>"><img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename))?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>           
        </div>
        <div class="thumb-attr">
        
        <div class="tit-item">
            <h3><a title="<?=htmlspecialchars($item->name_user);?>" href="<?=$item->url_path?><?=isset($urlAppend) ? $urlAppend : ''?>">
                <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
                </a>
            </h3>
        </div>
        
        <span class="res-ico">
        <?=$item->pwidth?>x<?=$item->pheight?>
        </span>    
        
        <span class="hits-ico">
        <?=$item->hits?>
        </span>               
        
        </div>
    </div>      
<?$counter++;endforeach; ?>
<?endif;?>