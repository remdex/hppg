<div class="float-break">
<? 
$counter = 1;
foreach ($items as $key => $itemFavorite) : 

$item = $itemFavorite->image;	
if ($item === false) continue;

?>
    <div id="image_thumb_<?=$item->pid?>" class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
        <div class="thumb-pic">
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
        
        <div class="right">
        <a class="cursor" onclick="return hw.deleteFavorite(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove from favorites');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove from favorites');?>"></a>
        </div>
           
        </div>
    </div>
        
<?$counter++;endforeach; ?>  

<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>


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