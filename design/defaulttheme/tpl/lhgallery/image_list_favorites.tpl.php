<div class="float-break">
<? foreach ($items as $key => $itemFavorite) : 
try{
	$item = $itemFavorite->image;
	$normalPath = ($item->pwidth < 450) ? erLhcoreClassDesign::imagePath($item->filepath.urlencode($item->filename)) : erLhcoreClassDesign::imagePath($item->filepath.'normal_'.urlencode($item->filename));
} catch (Exception $e){
	continue;
}
?>
    <div class="image-thumb" id="image_thumb_<?=$item->pid?>">
        <div class="right">
        <a class="cursor" onclick="return hw.deleteFavorite(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove from favorites');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove from favorites');?>" /></a>
        </div>
        <div class="thumb-pic">
            <a rel="<?=$normalPath?>" id="pic_attr_<?=$item->pid?>" href="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>"><img title="See full size" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename))?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>
        </div>
        <div class="thumb-attr">  
           <ul>
	            <li><?=$item->pwidth?>x<?=$item->pheight?></li>
	            <li><?=$item->hits?> watched</li>
	            <li><h3><a class="thmb" id="attr_<?=$item->pid?>" rel="<?=$normalPath?>" title="<?=htmlspecialchars($item->name_user);?>" href="<?=erLhcoreClassDesign::imagePath($item->filepath.$item->filename)?>">
	            <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
	            </a></h3></li>
	        </ul>            
        </div>
    </div>   
<?endforeach; ?>  
 <?php if (isset($pages)) : ?> 
    <div class="navigator" style="clear:left;">
    
    <?=$pages->display_pages();?> &nbsp;(<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list',"Found")?> - <?=$pages->items_total?>)</div>
<? endif;?>
 
</div>

<script type="text/javascript">
hw.imagePreview();
</script>