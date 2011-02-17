<div class="float-break">
<? 
$counter = 1;
foreach ($items as $key => $itemRecent) : 
try {
	$item = $itemRecent->image;
	
	if ($item === false) {
	    continue;
	}
	
} catch (Exception $e){
	continue;
}

?>
    <div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
        <div class="thumb-pic">
            <a class="inf-img" rel="<?=$item->pid?>"></a>
            <a href="<?=$item->url_path.$appendImageMode?>">
            
            <?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?>
                        
            </a>           
        </div>
        <div class="thumb-attr">
        
        <div class="tit-item">
            <h3><a title="<?=htmlspecialchars($item->name_user);?>" rel="<?=$item->url_path.$appendImageMode?>" href="<?=erLhcoreClassDesign::imagePath($item->filepath.$item->filename)?>">
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


<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>


 </div>

<script type="text/javascript"> 
  $('.thumb-attr a').each(function(index) {	
    	$(this).attr('href',$(this).attr('rel'));
  });
  hw.initInfoWindow('<?=base64_encode($appendImageMode)?>');
</script>