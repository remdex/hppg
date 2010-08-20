<div class="float-break">
<? 
$counter = 1;
foreach ($items as $item) : ?>
    <div class="album-thumb<?=!($counter % 4) ? ' left-thumb' : ''?>">
        <div class="content">        
            <div class="albthumb-img">
            <a href="<?=$item->url_path?>"><?php if ($item->album_thumb_path !== false) :?> 
            <img src="<?=erLhcoreClassDesign::imagePath($item->album_thumb_path)?>" alt="" width="130" height="140"/>
            <?php else :?>
            <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140"/>            
            <?php endif;?></a>      
            </div>
        
        
        <div class="tit-item">
       <h2><a title="<?=htmlspecialchars($item->title)?>" href="<?=$item->url_path?>"><?=htmlspecialchars($item->title)?></a></h2>
      
       </div>
       
       <span class="files-ico" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','files')?>">
        <?=$item->images_count;?>
       </span>
       
       </div>
       
    </div>   
<?
$counter++;
endforeach; ?>   
<?php if (isset($pages) && $pages->num_pages > 1) : ?>
 <div class="nav-container">    
    <div class="navigator">
    <div class="right found-total"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','Page %currentpage of %totalpage',array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','Found')?> - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?>
    </div>
</div>
<? endif;?> 
</div>

<script type="text/javascript">
$("div.album-thumb").mouseover(function() {
    $(this).addClass('image-thumb-shadow');
  }).mouseout(function(){
    $(this).removeClass('image-thumb-shadow');
  });
  
  $('.thumb-attr a').each(function(index) {	
    	$(this).attr('href',$(this).attr('rel'));
  })
</script>