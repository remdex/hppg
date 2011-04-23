<?php $skipImageListJS = true; ?>
<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads to albums');?> &raquo;</a></h1></div>
<?php $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('sort' => 'addtime DESC','offset' => 0, 'limit' => 4)); 
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php'));?>
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploads')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads');?> &raquo;</a></h1></div>
<?php 
$items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'filter' => array('approved' => 1), 'sort' => 'pid DESC','offset' => 0, 'limit' => 5));
$appendImageMode = '/(mode)/lastuploads';
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastcommented')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last commented');?> &raquo;</a></h1></div>
<?php 
$items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'filter' => array('approved' => 1), 'sort' => 'comtime DESC, pid DESC','offset' => 0, 'limit' => 5));
$appendImageMode = '/(mode)/lastcommented';
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last hits');?> &raquo;</a></h1></div>
<?php 
$items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'filter' => array('approved' => 1), 'sort' => 'mtime DESC, pid DESC','offset' => 0, 'limit' => 5));
$appendImageMode = '/(mode)/lasthits';
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastrated')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last rated');?> &raquo;</a></h1></div>
<?php 
$items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'filter' => array('approved' => 1), 'sort' => 'rtime DESC, pid DESC','offset' => 0, 'limit' => 5));
$appendImageMode = '/(mode)/lastrated';
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/popularrecent')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last 24 h.')?> - <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular');?> &raquo;</a></h1></div>
<?php 
$appendImageMode = '/(mode)/popularrecent';	 
$items = erLhcoreClassModelGalleryPopular24::getImages(array('disable_sql_cache' => true,'sort' => 'hits DESC, pid DESC','offset' => 0, 'limit' => 5));
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list_popularrecent.tpl.php'));?> 
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/ratedrecent')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last 24 h.')?> - <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?> &raquo;</a></h1></div>
<?php 
$appendImageMode = '/(mode)/ratedrecent';
$items = erLhcoreClassModelGalleryRated24::getImages(array('disable_sql_cache' => true,'offset' => 0, 'limit' => 5));       
if (!empty($items)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list_popularrecent.tpl.php'));?> 
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.
<?php endif;?>
</div>

<script> 
  $('.thumb-attr a').each(function(index) {	
    	$(this).attr('href',$(this).attr('rel'));
  });
  hw.initInfoWindow('<?=base64_encode($appendImageMode)?>');    
</script>