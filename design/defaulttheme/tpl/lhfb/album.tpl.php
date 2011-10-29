<div class="header-list">
<h1><?=htmlspecialchars($album['name'])?></h1>
</div>

<? if ($pages->items_total > 0) { ?> 
    <?php 
    $counter = 1;
    foreach ($photos['data'] as $photo) : 
    $namePhoto = isset($photo['name']) ? htmlspecialchars($photo['name']) : 'Preview version';
    ?>
        <div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
            <div class="thumb-pic">
                <a target="_blank" href="<?=$photo['link']?>">            
                    <img title="<?=$namePhoto?>" src="<?=$photo['picture']?>" alt="<?=$namePhoto?>" width="120" height="130">
                </a>           
            </div>
            <div class="thumb-attr">
            
            <div class="tit-item">
                <h3><a target="_blank" title="<?=$namePhoto?>" href="<?=$photo['link']?>">
                    <?=$namePhoto?>      
                    </a>
                </h3>
            </div>
            
            <span class="res-ico"><?=$photo['width']?>x<?=$photo['height']?></span>                
            <span class="right"><input class="itemPhoto image_import" title="Check photo to import" type="checkbox" name="PhotoID[]" value="<?=$photo['id']?>" /></span>
            </div>
        </div>
    <?php $counter++;endforeach;?>
    
    <div style="clear:both;padding-bottom:10px;">
    
        <div class="header-list">
            <h2>Import selected images to</h2>
        </div>

          To what album you want to import images, start typing album name and click search <div><input type="text" value="" class="inputfield newAlbumName">&nbsp;<input type="button" value="Search for album" class="default-button">&nbsp;<input type="button" value="Check all" class="default-button" id="checkAllButton"> </div>
          <div id="album_select_directory0" style="padding-top:5px;padding-bottom:5px;">
          <ul>
          <?php 
          $user = erLhcoreClassUser::instance();
          $albums = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('owner_id' => $user->getUserID())));
          foreach ($albums as $album) : ?>
            <li><input type="radio" name="AlbumDestinationDirectory0" value="<?=$album->aid?>" /><a href="<?=$album->url_path?>" target="_blank" title="Click to see target, new window will open"><?=htmlspecialchars($album->title)?></a></li>
          <?php endforeach;?>
          </ul>
          </div>
          <input type="button" name="moveSelectedPhotos" <?php if (empty($albums)) : ?>style="display:none"<?php endif;?> id="moveAction" value="Import selected images" class="default-button">  

          &nbsp;<span id="total_to_import"></span>
          
          <script>
            $('#checkAllButton').click(function() { 
               $( '.itemPhoto' ).each( function() {         
            		$( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
            	})    
            });
            
            $('.newAlbumName').change(function(){	    
            	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumnamesuggest')?>/0/"+escape($(this).val()), {} , function(data){	
                               $('#album_select_directory0').html(data.result);                       
                               if (data.error == 'false'){
                                    $('#album_select_directory0 input').eq(0).attr("checked","checked");
                                    $('#moveAction').show();
                               } else {
                                   $('#moveAction').hide();
                               }                       
                	});	
            });
            
            $('#moveAction').click(function() { 
                if ($('.image_import:checked').size() > 0) {
                importPhoto();
                } else {
                    alert('Please choose atleast one image!');
                }
            });
            
            function importPhoto()
            {
               if ($('input[name=AlbumDestinationDirectory0]:checked').val() != undefined) { 
                   if ($('.image_import:checked').size() > 0) {
                       $('#total_to_import').html($('.image_import:checked').size() + ' images to import...');
                       $('#total_to_import').addClass('spinning-now');
                       
                       $.getJSON("<?=erLhcoreClassDesign::baseurl('fb/importfbphoto')?>/"+$('input[name=AlbumDestinationDirectory0]:checked').val()+"/"+$('.image_import:checked').eq(0).val(), {} , function(data){
                              $('.image_import:checked').eq(0).removeClass('image_import');	
                    		  importPhoto();
                    	});
                   } else { 
                        $('#total_to_import').removeClass('spinning-now');
                        $('#total_to_import').html('All images were imported...');
                   }                	
               } else {
                   alert('Please choose album');
               }
            }
          </script>
     </div>
     
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? } else { ?>
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.</p>
<? } ?>