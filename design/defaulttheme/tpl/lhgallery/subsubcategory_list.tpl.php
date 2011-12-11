<div class="float-break">
<?php 
$counter = 0;
foreach ($subsubcategorys as $subcategory) : ?>   
<div class="subcategory<?=$counter % 2 ? ' mod-cat' : ''?>">  
    <div class="cont-sub">
       
        
        <span class="album-ico right" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list','albums')?>">
        <?=$subcategory->albums_count?>
        </span> 
            
        <span class="files-ico right" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list','images')?>">
        <?=$subcategory->images_count;?>
       </span>
        
      
        <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a></h3>
        
        
        <? if ($subcategory->description != '') : ?>
        <p><?=$subcategory->description?></p>
        <?endif;?>
        
        
    </div>
</div>
<?php $counter++;endforeach;?>    
</div>