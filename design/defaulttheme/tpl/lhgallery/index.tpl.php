<?php $skipImageListJS = true; ?>


<div class="category float-break pallete-sub">
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Search by color and keyword, just try to see how fun it is!')?></h2></div>
    <form action="<?=erLhcoreClassDesign::baseurl('gallery/search')?>">
    <input type="text" autocomplete="off" id="KeywordColorSearch" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Enter keyword or phrase')?>" class="keywordField inputfield" name="SearchText" value="">&nbsp;<input type="submit" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Search entire gallery')?>" class="default-button" name="doSearch" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Search')?>">&nbsp;<i>(<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','enter keyword and click color, or just click color')?>)</i>
    </form>
    
    <br />
    <br />
    
    <div id="pallete-include" class="pallete-main float-break pallete-sub left">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Include color')?></h3>
    <?php 
    $counter = 0;
    $arrayFormated = array();
    foreach (erLhcoreClassModelGalleryPallete::getList() as $pallete){
    $arrayFormated[$counter][] = $pallete;
   
    $counter = ($counter == 10) ? 0 : $counter;   
    $counter++;
    };    
     foreach ($arrayFormated as $package):
     foreach ($package as $pallete): ?>
     
    <div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);"><a rel="<?=$pallete->id?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/<?=$pallete->id?>"></a></div>
    <?endforeach;?>
    <?endforeach;?>
    </div>
    
    <div id="pallete-exclude" class="pallete-main float-break pallete-sub right">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Exclude color')?></h3>
    <?php 
    $counter = 0;
    $arrayFormated = array();
    foreach (erLhcoreClassModelGalleryPallete::getList() as $pallete){
    $arrayFormated[$counter][] = $pallete;
   
    $counter = ($counter == 10) ? 0 : $counter;   
    $counter++;
    };    
     foreach ($arrayFormated as $package):
     foreach ($package as $pallete): ?>
     
    <div style="background-color:rgb(<?=$pallete->red?>,<?=$pallete->green?>,<?=$pallete->blue?>);"><a rel="<?=$pallete->id?>" href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(ncolor)/<?=$pallete->id?>"></a></div>
    <?endforeach;?>
    <?endforeach;?>
    </div>
</div>



<div class="category">
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads to albums');?> &raquo;</a></h1></div>
<?php $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter'=> array('hidden' => 0),'sort' => 'addtime DESC','offset' => 0, 'limit' => 4)); 
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
  $('#KeywordColorSearch').change(function(){ 
      var keyword = $(this).val();
      if ($(this).val() == '') {
            $('#pallete-include a').each(function(index) {
                	$(this).attr('href',"<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(color)/"+$(this).attr('rel'));
            });
            $('#pallete-exclude a').each(function(index) {
                	$(this).attr('href',"<?=erLhcoreClassDesign::baseurl('gallery/color')?>/(ncolor)/"+$(this).attr('rel'));
            });
      } else { 
            $('#pallete-include a').each(function(index) {
                	$(this).attr('href',"<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/"+escape(keyword)+"/(color)/"+$(this).attr('rel'));
            }); 
            $('#pallete-exclude a').each(function(index) {
                	$(this).attr('href',"<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/"+escape(keyword)+"/(ncolor)/"+$(this).attr('rel'));
            });
      }
  });   
</script>