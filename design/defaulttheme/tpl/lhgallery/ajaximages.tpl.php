<? 
if (count($imagesAjax) > 0) :
$counter = 1;
foreach ($imagesAjax as $key => $item) : 
?>
<div class="image-thumb">
        <div class="thumb-pic">
            <a class="inf-img" rel="<?=$item->pid?>"></a>
            <a rel="<?=$item->pid?>" href="<?=$item->url_path.$urlAppend?>">
            
            <?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?>
            
            </a>           
        </div>
        <div class="thumb-attr">
        
        <div class="tit-item">
            <h3><a title="<?=htmlspecialchars($item->name_user);?>" href="<?=$item->url_path.$urlAppend?>">
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
<script>
hw.initInfoWindow('<?=base64_encode($urlAppend)?>');
</script>