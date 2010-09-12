<div class="float-break">
<? 
$counter = 1;
foreach ($items as $key => $item) : 
?>
    <div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
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
        
        <span class="res-ico">
        <?=$item->pwidth?>x<?=$item->pheight?>
        </span>    
        
        <span class="hits-ico">
        <?=$item->hits?>
        </span>               
        
        </div>
    </div>   
<?$counter++;endforeach; ?>    
 <?php if (isset($pages)) : ?>
 <div class="nav-container">
    <div class="navigator">
    <div class="right found-total"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','Found')?> - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?></div>
 </div>   
<? endif;?>
</div>
<script type="text/javascript"> 
  $('.thumb-attr a').each(function(index) {	
    	$(this).attr('href',$(this).attr('rel'));
  })
</script>
