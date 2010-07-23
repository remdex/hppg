<?php
$items=erLhcoreClassModelGalleryCategory::getParentCategories();
if (count($items) > 0) {
?>
<div id="categories">
	<ul>
		<li><span class='dvcat' style="margin-left:15px;"><a rel="0" class="cat-href" href="<?=erLhcoreClassDesign::baseurl('/gallery/admincategorys/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Root category');?></a></span>
		
		<ul>
		<?php
			foreach ($items as $key => $item) {
				$subcat = erLhcoreClassModelGalleryCategory::getParentCategories($item->cid);
				$subalbum = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $item->cid)));
				if ((count($subcat) + count($subalbum)) > 0) : ?>
					<li id="c<?=$item->cid?>" ><a href="<?=$item->cid?>" class='isplesti'><img src="<?=erLhcoreClassDesign::design('images/gallery/plus.gif')?>" alt="" align="top" /></a><span class="dvcat"><a class="cat-href<?php if (isset($Result['path_cid']) && in_array($item->cid,$Result['path_cid'])){ print ' selected'; }?>" rel="<?=$item->cid?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/admincategorys')?>/<?=$item->cid?>" ><?=htmlspecialchars($item->name)?></a></span></li>
				<?php else : ?> 
					<li><span class="none"><span class="dvcat"><a class="cat-href<?php if (isset($Result['path_cid']) && in_array($item->cid,$Result['path_cid'])){ print ' selected'; }?>" rel="<?=$item->cid?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/admincategorys')?>/<?=$item->cid?>"><?=htmlspecialchars($item->name)?></a></span></span></li>
				<?php endif;
			}
		?>
		</ul>
		</li>
	</ul>
</div>

<?php
}
?>
<script type="text/javascript">

var selectedItems = [<? if (isset($Result['path_cid'])) { echo implode(',',$Result['path_cid']); } ?>];
	
	$(document).ready(function(event) {
		$('div#categories ul li a.isplesti').each(function(index) {
			var sausainis = $.cookie("category");
    		if (sausainis) {
    			var masyvas = sausainis.split(",");
    			if (jQuery.inArray($(this).attr('href'), masyvas) >= 0) {
    				testas($(this));
    			}
    		}
  		});
   	});
   	
	$("div#categories ul li a").live('click', function(event) {	
		var linkas = $(this);
		if ($(this).hasClass('isplesti')) {
			testas($(this));
			var sausainis = $.cookie("category");
			if (sausainis) {
				var masyvas = $.cookie("category").split(",");
				var arYra = 0;
				for (ind in masyvas) {
					if (masyvas[ind] == $(linkas).attr('href'))
						arYra = 1;
				}
				if (arYra == 0) {
					masyvas[masyvas.length] = $(linkas).attr('href');
					var sausainis = masyvas.join(",");
					jQuery.cookie("category", sausainis, { path: '/', expires: 7 });
				}
			} else {				jQuery.cookie("category", $(linkas).attr('href'), { path: '/', expires: 7 });
			}
			event.preventDefault();
		} else if ($(this).hasClass('sutraukti')) {
			$(this).parent().parent().find("li#cat_"+$(linkas).attr('href')).remove();
			$(this).removeClass("sutraukti");
			$(this).addClass("isplesti");
			$(this).parent().removeClass('sakaliukas');
			if ($(this).hasClass('last')){
				$(this).html("<img src='<?=erLhcoreClassDesign::design('images/gallery/plus2.gif');?>' alt='' align='top' />");}
			else{
				$(this).html("<img src='<?=erLhcoreClassDesign::design('images/gallery/plus.gif');?>' alt='' align='top' />");}
				
			var sausainis = $.cookie("category");
			if (sausainis) {
				var masyvas = $.cookie("category").split(",");
				sausainis = "";
				for (ind in masyvas) {
					if (masyvas[ind] != $(linkas).attr('href')) {
						sausainis += ","+masyvas[ind];
					}
				}
				$.cookie("category", sausainis.substr(1), { path: '/', expires: 7 });
			} else {
				$.cookie("category", null);
			}
			event.preventDefault();
		}
	});
	
	
	
			$(document).ready(function(){
			
				$('div#categories ul li a').live('contextmenu',function(e){
				var $cmenu = $('.vmenu');
				$('<div class="overlay"></div>').css({left : '0px', top : '0px',position: 'absolute', width: '100%', height: '100%', zIndex: '100' }).click(function() {
					$(this).remove();
					$cmenu.hide();
				}).bind('contextmenu' , function(){return false;}).appendTo(document.body);
				$('.vmenu').css({ left: e.pageX, top: e.pageY, zIndex: '101' }).show();
				$('.vmenu h3').html($(this).html());
				
				
				$('.vmenu .open-link').attr('href',$(this).attr('href'));
				
				if ($(this).hasClass('al-href')){
					$('.vmenu .edit-link-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/albumeditadmin')?>'+"/"+$(this).attr('rel'));
					$('.vmenu .album-del-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/deletealbumadmin')?>'+"/"+$(this).attr('rel'));
					$('.vmenu .add-images-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/addimagesadmin')?>'+"/"+$(this).attr('rel'));
				
					$('.vmenu .cat-new-li').hide();
					$('.vmenu .album-new-li').hide();	
					
					$('.vmenu .album-del-li').show();
					$('.vmenu .add-images-li').show();
					$('.vmenu .cat-del-li').hide();			
								
				} else {
					$('.vmenu .edit-link-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/editcategory')?>'+"/"+$(this).attr('rel'));
					$('.vmenu .album-new-li').show();
					$('.vmenu .cat-new-li').show();
					$('.vmenu .album-del-li').hide();
					$('.vmenu .add-images-li').hide();
					$('.vmenu .cat-del-li').show();
					
					$('.vmenu .album-new-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/createalbumadmin')?>'+"/"+$(this).attr('rel'));
					$('.vmenu .cat-new-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/createcategory')?>'+"/"+$(this).attr('rel'));
					$('.vmenu .cat-del-li a').attr('href','<?=erLhcoreClassDesign::baseurl('gallery/deletecategory')?>'+"/"+$(this).attr('rel'));
				}
				
				if ($(this).attr('rel') == 0)
				{
					$('.vmenu .cat-del-li').hide();
					$('.vmenu .edit-link-li').hide();
					$('.vmenu .album-new-li').hide();
					
				}
					
				return false;
				 });
	
				 $('.vmenu .first_li').live('click',function() {
					if( $(this).children().size() == 1 ) {						
						$('.vmenu').hide();
						$('.overlay').hide();
					}
				 });
	
				 $('.vmenu .inner_li span').live('click',function() {
						alert($(this).text());
						$('.vmenu').hide();
						$('.overlay').hide();
				 });
	
		
				$(".first_li , .sec_li, .inner_li span").hover(function () {
					$(this).css({backgroundColor : '#E0EDFE' , cursor : 'pointer'});
				if ( $(this).children().size() >0 )
						$(this).find('.inner_li').show();	
						$(this).css({cursor : 'default'});
				}, 
				function () {
					$(this).css('background-color' , '#fff' );
					$(this).find('.inner_li').hide();
				});
			
			});
			
		function testas (linkas) {
    		var id = $(linkas).attr('href');
			var htmlli = $(document.createElement('li'));
			htmlli.attr("id","cat_"+id);
			var htmlul = $(document.createElement('ul'));
			htmlul.addClass("sakaliukas");
			if ($(linkas).parent().hasClass('last'))
				htmlul.addClass("notransparent");
			$(linkas).removeClass("isplesti");
			$(linkas).addClass("sutraukti");
			if ($(linkas).hasClass('last')){
				$(linkas).html("<img src='<?=erLhcoreClassDesign::design('images/gallery/minus2.gif');?>' alt='' align='top' />");
			}else{
				$(linkas).html("<img src='<?=erLhcoreClassDesign::design('images/gallery/minus.gif');?>' alt='' align='top' />");}
			$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/catjson')?>"+"/"+$(linkas).attr('href'), function(data) {
				$(data).each(function(index) {
					var html = $(document.createElement('li'));
					var htmla = $(document.createElement('a'));
					var htspan = "";
					if (data.length == (index+1)) {
						html.addClass("last");
					}	
					if (data[index].type == 2 && data[index].haschild == 1) {
						html.attr("id","c"+data[index].id);
						htmla.attr('href',data[index].id);
						htmla.addClass("isplesti");
						if (data.length == (index+1)) {
							htmla.addClass("last");
							htmla.html("<img src='<?=erLhcoreClassDesign::design('images/gallery/plus2.gif');?>' alt='' align='top' />");
						} else {
							htmla.html("<img src='<?=erLhcoreClassDesign::design('images/gallery/plus.gif');?>' alt='' align='top' />");
						}
						html.append(htmla);
					} else if (data.length == (index+1)) {
						htspan = $(document.createElement('span'));
						htspan.addClass('dvlast');
					} else {
						htspan = $(document.createElement('span'));
						htspan.addClass('dvnone');
					}
					var htmls = $(document.createElement('span'));
					if (data[index].type == 1) {
						htmls.addClass('dvalbum');
					} else {
						htmls.addClass('dvcat');
					}
					var htmla2 = $(document.createElement('a'));

					if (data[index].type == 1) {
						htmla2.attr('href',"<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages/')?>"+data[index].id);
						htmla2.addClass('al-href');	

						if (<?=isset($Result['album_id']) ? $Result['album_id'] : 0?> == data[index].id){
							htmla2.addClass("selected");
						}
											
					} else {
						htmla2.attr('href',"<?=erLhcoreClassDesign::baseurl('gallery/admincategorys/')?>"+data[index].id);
						htmla2.addClass('cat-href');
																		
						if (jQuery.inArray(parseInt(data[index].id), selectedItems) >= 0) {													
							htmla2.addClass("selected");
						}
					}
					
					htmla2.attr('rel',+data[index].id);
					htmla2.html(data[index].name);
					htmls.append(htmla2);
					if (htspan) {
						htspan.append(htmls);
						html.append(htspan);
					} else {
						html.append(htmls);	
					}
					htmlul.append(html);
					var sausainis = $.cookie("category");
					var masyvas = sausainis.split(",");
    				if (data[index].type == 2 && data[index].haschild == 1 && jQuery.inArray(data[index].id, masyvas) >= 0) {
    					testas(htmla);
    				}
    				if (data.length == (index+1)) {
						htmlli.append(htmlul);
						$("#c"+id).after(htmlli);
					}
				});
			});
		}
</script>
<div class="vmenu">
		<h3></h3>
		<div class="first_li"><span><a class="open-link" href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Open');?></a></span></div>
		<div class="first_li edit-link-li"><span><a href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Edit');?></a></span></div>		
		<div class="first_li album-new-li"><span><a href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','New album');?></a></span></div>
		<div class="first_li cat-new-li"><span><a href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','New category');?></a></span></div>			
		<div class="first_li album-del-li"><span><a href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Delete album');?></a></span></div>
		<div class="first_li cat-del-li"><span><a href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Delete category');?></a></span></div>
		<div class="first_li add-images-li"><span><a href=""><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Add images');?></a></span></div>					
</div>

