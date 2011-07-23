<div id="imageInfoWindowAlbum">
<ul>
    <li><h3><a title="View image" href="<?=$album->url_path?>"><?=htmlspecialchars($album->title);?></a></h3>
</ul>

<?php 
$appendImageMode = '';
$items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'use_index' => 'pid_6', 'disable_sql_cache' => false,'filter' => array('aid' => $album->aid),'offset' => 0, 'limit' => 3)) ?>

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?>   

<?php if( $album->description != '') : ?>
<div class="dominant-colors nml nfloat"><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($album->description))?></div>
<?endif;?>


</div>