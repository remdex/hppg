<div class="float-break">
<? 
$counter = 1;
foreach ($items as $item) : 

?>
    <div class="album-thumb<?=!($counter % 4) ? ' left-thumb' : ''?>">
        <div class="content">        
            <div class="albthumb-img">
            <a title="<?=htmlspecialchars($item->title)?>" href="<?=$item->url_path?>"><?php if ($item->album_thumb_path !== false) :?> 
            <img src="<?=erLhcoreClassDesign::imagePath($item->album_thumb_path)?>" alt="" width="130" height="140">
            <?php else :?>
            <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140">            
            <?php endif;?></a>      
            </div>
        
            <div class="album-attr">
                <div class="tit-item">
                <h2><a title="<?=htmlspecialchars($item->title)?>" href="<?=$item->url_path?>"><?=htmlspecialchars($item->title)?></a></h2>      
                </div>
               
                <span class="files-ico" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','files')?>">
                <?=$item->images_count;?>
                </span>
               
               <span class="right">
                <? if (date('Ymd') == date('Ymd',$item->addtime)) : ?>
                    <?=date('H:i:s',$item->addtime)?>
                <?php else : ?>
                    <?=date('Y-m-d',$item->addtime)?>
                <?php endif;?>
                </span>       
            </div>
       </div>
       
    </div>   
<?
$counter++;
endforeach; ?> 
  
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>

</div>