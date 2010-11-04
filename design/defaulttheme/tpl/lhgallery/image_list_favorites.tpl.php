<div class="float-break">
<? 
$counter = 1;
foreach ($items as $key => $itemFavorite) : 

$item = $itemFavorite->image;	
if ($item === false) continue;

?>
    <div id="image_thumb_<?=$item->pid?>" class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
        <div class="thumb-pic">
            <a href="<?=$item->url_path?><?=isset($appendImageMode) ? $appendImageMode : ''?>">
                       
            <?php if ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) : ?>
                <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
            <?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV) : ?>        
                                    
                <?php if ($item->has_preview) : ?>
                    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace('.ogv','.jpg',$item->filename)),true,$item->pid)?>" alt="<?=htmlspecialchars($item->name_user);?>">
                <?php else : ?>
                    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/ogv.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
                <?php endif;?>
                
            <?php elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF) : ?>                               
               
                    <img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::design('images/icons/swf.jpg')?>" alt="<?=htmlspecialchars($item->name_user);?>">
                
            <?php endif;?>
            
            </a>           
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