<div class="header-list">
<div class="right"><a href="<?=erLhcoreClassDesign::baseurl('gallery/movebyresolution/')?><?=$album->aid?>">Move images by resolution</a></div>


<h1><?=htmlspecialchars($album->title)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>
         
  <? 
            $items = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'albumlist_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$album->aid),'filter' => array('aid' => $album->aid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
  
  <form action="" method="post">
  
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_image_list.tpl.php'));?> 
       

   
  <fieldset><legend>Change selected images album</legend> 
  Move selected images to 
  <div><input type="text" class="default-input newAlbumName" value="" > <input class="default-button" type="button" value="Search album" /></div>
  
  <div id="album_select_directory0"></div>
   
  <input type="submit" class="default-button" value="Move photos" id="moveAction" style="display:none" name="moveSelectedPhotos" />  
  <input type="button" id="checkAllButton" class="default-button" value="Check all" />  
  
  </fieldset>
  
  
  </form>
  
  
<script>
$('.newAlbumName').change(function(){	    
	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumnamesuggest/')?>0/"+escape($(this).val()), {} , function(data){	
                   $('#album_select_directory0').html(data.result);                       
                   if (data.error == 'false'){
                        $('#album_select_directory0 input').eq(0).attr("checked","checked");
                        $('#moveAction').show();
                   } else {
                       $('#moveAction').hide();
                   }                       
    	});	
});
$('#checkAllButton').click(function() { 
   $( '.itemPhoto' ).each( function() {         
		$( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
	})    
});
</script>
  
  
  
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','No records.')?></p>

<? } ?>

