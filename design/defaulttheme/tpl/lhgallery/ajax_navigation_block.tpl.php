<div id="ajax-navigator-content" class="float-break">

    <div class="right-ajax"<?=$hasRight === false ? ' style="display:none"' : ''?>>
        <a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$rightImagePID?><?=$urlAppend?>"></a>
    </div>
    
    <div class="left-ajax"<?=$hasLeft === false ? ' style="display:none"' : ''?>>
        <a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$leftImagePID?><?=$urlAppend?>"></a>
    </div>
    
    <div class="navigator-ajax float-break" id="images-ajax-container">
    <? foreach ($imagesAjax as $key => $item) : ?>
        <div class="image-thumb<?=$item->pid == $image->pid ? ' image-thumb-cur' : ''?>">
            <div class="thumb-pic">
                <a rel="<?=$item->pid?>" href="<?=$item->url_path?><?=isset($urlAppend) ? $urlAppend : ''?>"><img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename))?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>           
            </div>
            <div class="thumb-attr">
            
            <div class="tit-item">
                <h3><a title="<?=htmlspecialchars($item->name_user);?>" href="<?=$item->url_path?><?=$urlAppend?>">
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
    <? endforeach; ?> 
    </div>

</div>