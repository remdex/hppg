<div class="float-break">
<? 
$counter = 1;
foreach ($items as $key => $itemFavorite) : 
try {
	$item = $itemFavorite->image;
	$normalPath = ($item->pwidth < 450) ? erLhcoreClassDesign::imagePath($item->filepath.urlencode($item->filename)) : erLhcoreClassDesign::imagePath($item->filepath.'normal_'.urlencode($item->filename));
} catch (Exception $e){
	continue;
}
?>

    <div id="image_thumb_<?=$item->pid?>" class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
        <div class="thumb-pic">
            <a href="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>"><img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>"></a>           
        </div>
        <div class="thumb-attr">
        
        <div class="tit-item">
            <h3><a title="<?=htmlspecialchars($item->name_user);?>" rel="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>" href="<?=erLhcoreClassDesign::imagePath($item->filepath.$item->filename)?>">
                <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
                </a>
            </h3>
        </div>
        
        <div class="right">
        <a class="cursor" onclick="return hw.deleteFavorite(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove from favorites');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove from favorites');?>"></a>
        </div>
           
        </div>
    </div>
        
<?$counter++;endforeach; ?>  
<?php if (isset($pages)) : ?> 
<div class="nav-container">
    <div class="navigator">    
        <div class="right found-total">(<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list',"Found")?> - <?=$pages->items_total?>)</div>
    <?=$pages->display_pages();?>
    </div>
 </div>
<? endif;?>
 </div>

<script type="text/javascript">
$("div.image-thumb").mouseover(function() {
    $(this).addClass('image-thumb-shadow');
  }).mouseout(function(){
    $(this).removeClass('image-thumb-shadow');
  });
  
  $('.thumb-attr a').each(function(index) {	
    	$(this).attr('href',$(this).attr('rel'));
  })
</script>