(function(c,lb){var v="none",N="LoadedContent",b=false,x="resize.",o="y",r="auto",f=true,u="click",O="nofollow",q="on",m="x";function e(a,b){a=a?' id="'+j+a+'"':"";b=b?' style="'+b+'"':"";return c("<div"+a+b+"/>")}function p(a,b){b=b===m?n.width():n.height();return typeof a==="string"?Math.round(a.match(/%/)?b/100*parseInt(a,10):parseInt(a,10)):a}function U(b,d){b=c.isFunction(b)?b.call(d):b;return a.photo||b.match(/\.(gif|png|jpg|jpeg|bmp)(?:\?([^#]*))?(?:#(\.*))?$/i)}function eb(a){for(var b in a)if(c.isFunction(a[b])&&b.substring(0,2)!==q)a[b]=a[b].call(l);a.rel=a.rel||l.rel||O;a.href=a.href||c(l).attr("href");a.title=a.title||l.title;return a}function y(b,a){a&&a.call(l);c.event.trigger(b)}function mb(){var c,b=j+"Slideshow_",e,f;if(a.slideshow&&i[1]){e=function(){z.text(a.slideshowStop).bind(V,function(){c=setTimeout(d.next,a.slideshowSpeed)}).bind(W,function(){clearTimeout(c)}).one(u,function(){f()});h.removeClass(b+"off").addClass(b+q)};f=function(){clearTimeout(c);z.text(a.slideshowStart).unbind(V+" "+W).one(u,function(){e();c=setTimeout(d.next,a.slideshowSpeed)});h.removeClass(b+q).addClass(b+"off")};z.bind(fb,function(){z.unbind();clearTimeout(c);h.removeClass(b+"off "+b+q)});a.slideshowAuto?e():f()}}function gb(b){if(!P){l=b;a=eb(c.extend({},c.data(l,s)));i=c(l);g=0;if(a.rel!==O){i=c("."+I).filter(function(){return (c.data(this,s).rel||this.rel)===a.rel});g=i.index(l);if(g===-1){i=i.add(l);g=i.length-1}}if(!w){w=H=f;h.show();X=l;try{X.blur()}catch(e){}A.css({opacity:+a.opacity,cursor:a.overlayClose?"pointer":r}).show();a.w=p(a.initialWidth,m);a.h=p(a.initialHeight,o);d.position(0);Y&&n.bind(x+Q+" scroll."+Q,function(){A.css({width:n.width(),height:n.height(),top:n.scrollTop(),left:n.scrollLeft()})}).trigger("scroll."+Q);y(hb,a.onOpen);Z.add(J).add(K).add(z).add(ab).hide();bb.html(a.close).show()}d.load(f)}}var ib={transition:"elastic",speed:300,width:b,initialWidth:"600",innerWidth:b,maxWidth:b,height:b,initialHeight:"450",innerHeight:b,maxHeight:b,scalePhotos:f,scrolling:f,inline:b,html:b,iframe:b,photo:b,href:b,title:b,rel:b,opacity:.9,preloading:f,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",open:b,loop:f,slideshow:b,slideshowAuto:f,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:b,onLoad:b,onComplete:b,onCleanup:b,onClosed:b,overlayClose:f,escKey:f,arrowKey:f},s="colorbox",j="cbox",hb=j+"_open",W=j+"_load",V=j+"_complete",jb=j+"_cleanup",fb=j+"_closed",R=j+"_purge",kb=j+"_loaded",D=c.browser.msie&&!c.support.opacity,Y=D&&c.browser.version<7,Q=j+"_IE6",A,h,E,t,cb,db,T,S,i,n,k,L,M,ab,Z,z,K,J,bb,F,G,B,C,l,X,g,a,w,H,P=b,d,I=j+"Element";d=c.fn[s]=c[s]=function(b,e){var a=this,d;if(!a[0]&&a.selector)return a;b=b||{};if(e)b.onComplete=e;if(!a[0]||a.selector===undefined){a=c("<a/>");b.open=f}a.each(function(){c.data(this,s,c.extend({},c.data(this,s)||ib,b));c(this).addClass(I)});d=b.open;if(c.isFunction(d))d=d.call(a);d&&gb(a[0]);return a};d.init=function(){var l="hover",m="clear:left";n=c(lb);h=e().attr({id:s,"class":D?j+"IE":""});A=e("Overlay",Y?"position:absolute":"").hide();E=e("Wrapper");t=e("Content").append(k=e(N,"width:0; height:0; overflow:hidden"),M=e("LoadingOverlay").add(e("LoadingGraphic")),ab=e("Title"),Z=e("Current"),K=e("Next"),J=e("Previous"),z=e("Slideshow").bind(hb,mb),bb=e("Close"));E.append(e().append(e("TopLeft"),cb=e("TopCenter"),e("TopRight")),e(b,m).append(db=e("MiddleLeft"),t,T=e("MiddleRight")),e(b,m).append(e("BottomLeft"),S=e("BottomCenter"),e("BottomRight"))).children().children().css({"float":"left"});L=e(b,"position:absolute; width:9999px; visibility:hidden; display:none");c("body").prepend(A,h.append(E,L));t.children().hover(function(){c(this).addClass(l)},function(){c(this).removeClass(l)}).addClass(l);F=cb.height()+S.height()+t.outerHeight(f)-t.height();G=db.width()+T.width()+t.outerWidth(f)-t.width();B=k.outerHeight(f);C=k.outerWidth(f);h.css({"padding-bottom":F,"padding-right":G}).hide();K.click(d.next);J.click(d.prev);bb.click(d.close);t.children().removeClass(l);c("."+I).live(u,function(a){if(!(a.button!==0&&typeof a.button!=="undefined"||a.ctrlKey||a.shiftKey||a.altKey)){a.preventDefault();gb(this)}});A.click(function(){a.overlayClose&&d.close()});c(document).bind("keydown",function(b){if(w&&a.escKey&&b.keyCode===27){b.preventDefault();d.close()}if(w&&a.arrowKey&&!H&&i[1])if(b.keyCode===37&&(g||a.loop)){b.preventDefault();J.click()}else if(b.keyCode===39&&(g<i.length-1||a.loop)){b.preventDefault();K.click()}})};d.remove=function(){h.add(A).remove();c("."+I).die(u).removeData(s).removeClass(I)};d.position=function(f,c){function d(a){cb[0].style.width=S[0].style.width=t[0].style.width=a.style.width;M[0].style.height=M[1].style.height=t[0].style.height=db[0].style.height=T[0].style.height=a.style.height}var e,i=Math.max(document.documentElement.clientHeight-a.h-B-F,0)/2+n.scrollTop(),g=Math.max(n.width()-a.w-C-G,0)/2+n.scrollLeft();e=h.width()===a.w+C&&h.height()===a.h+B?0:f;E[0].style.width=E[0].style.height="9999px";h.dequeue().animate({width:a.w+C,height:a.h+B,top:i,left:g},{duration:e,complete:function(){d(this);H=b;E[0].style.width=a.w+C+G+"px";E[0].style.height=a.h+B+F+"px";c&&c()},step:function(){d(this)}})};d.resize=function(b){if(w){b=b||{};if(b.width)a.w=p(b.width,m)-C-G;if(b.innerWidth)a.w=p(b.innerWidth,m);k.css({width:a.w});if(b.height)a.h=p(b.height,o)-B-F;if(b.innerHeight)a.h=p(b.innerHeight,o);if(!b.innerHeight&&!b.height){b=k.wrapInner("<div style='overflow:auto'></div>").children();a.h=b.height();b.replaceWith(b.children())}k.css({height:a.h});d.position(a.transition===v?0:a.speed)}};d.prep=function(o){var f="hidden";function m(t){var m,r,f,o,e=i.length,q=a.loop;d.position(t,function(){if(w){D&&p&&k.fadeIn(100);k.show();y(kb);ab.show().html(a.title);if(e>1){Z.html(a.current.replace(/\{current\}/,g+1).replace(/\{total\}/,e)).show();K[q||g<e-1?"show":"hide"]().html(a.next);J[q||g?"show":"hide"]().html(a.previous);m=g?i[g-1]:i[e-1];f=g<e-1?i[g+1]:i[0];if(a.slideshow){z.show();g===e-1&&!q&&h.is("."+j+"Slideshow_on")&&z.click()}if(a.preloading){o=c.data(f,s).href||f.href;r=c.data(m,s).href||m.href;if(U(o,f))c("<img/>")[0].src=o;if(U(r,m))c("<img/>")[0].src=r}}M.hide();if(a.transition==="fade")h.fadeTo(l,1,function(){if(D)h[0].style.filter=b});else if(D)h[0].style.filter=b;n.bind(x+j,function(){d.position(0)});y(V,a.onComplete)}})}if(w){var p,l=a.transition===v?0:a.speed;n.unbind(x+j);k.remove();k=e(N).html(o);k.hide().appendTo(L.show()).css({width:function(){a.w=a.w||k.width();a.w=a.mw&&a.mw<a.w?a.mw:a.w;return a.w}(),overflow:a.scrolling?r:f}).css({height:function(){a.h=a.h||k.height();a.h=a.mh&&a.mh<a.h?a.mh:a.h;return a.h}()}).prependTo(t);L.hide();c("#"+j+"Photo").css({cssFloat:v,marginLeft:r,marginRight:r});Y&&c("select").not(h.find("select")).filter(function(){return this.style.visibility!==f}).css({visibility:f}).one(jb,function(){this.style.visibility="inherit"});a.transition==="fade"?h.fadeTo(l,0,function(){m(0)}):m(l)}};d.load=function(t){var n,b,r,q=d.prep;H=f;l=i[g];t||(a=eb(c.extend({},c.data(l,s))));y(R);y(W,a.onLoad);a.h=a.height?p(a.height,o)-B-F:a.innerHeight&&p(a.innerHeight,o);a.w=a.width?p(a.width,m)-C-G:a.innerWidth&&p(a.innerWidth,m);a.mw=a.w;a.mh=a.h;if(a.maxWidth){a.mw=p(a.maxWidth,m)-C-G;a.mw=a.w&&a.w<a.mw?a.w:a.mw}if(a.maxHeight){a.mh=p(a.maxHeight,o)-B-F;a.mh=a.h&&a.h<a.mh?a.h:a.mh}n=a.href;M.show();if(a.inline){e().hide().insertBefore(c(n)[0]).one(R,function(){c(this).replaceWith(k.children())});q(c(n))}else if(a.iframe){h.one(kb,function(){var b=c("<iframe name='"+(new Date).getTime()+"' frameborder=0"+(a.scrolling?"":" scrolling='no'")+(D?" allowtransparency='true'":"")+" style='width:100%; height:100%; border:0; display:block;'/>");b[0].src=a.href;b.appendTo(k).one(R,function(){b[0].src="about:blank"})});q(" ")}else if(a.html)q(a.html);else if(U(n,l)){b=new Image;b.onload=function(){var e;b.onload=null;b.id=j+"Photo";c(b).css({border:v,display:"block",cssFloat:"left"});if(a.scalePhotos){r=function(){b.height-=b.height*e;b.width-=b.width*e};if(a.mw&&b.width>a.mw){e=(b.width-a.mw)/b.width;r()}if(a.mh&&b.height>a.mh){e=(b.height-a.mh)/b.height;r()}}if(a.h)b.style.marginTop=Math.max(a.h-b.height,0)/2+"px";i[1]&&(g<i.length-1||a.loop)&&c(b).css({cursor:"pointer"}).click(d.next);if(D)b.style.msInterpolationMode="bicubic";setTimeout(function(){q(b)},1)};setTimeout(function(){b.src=n},1)}else e().appendTo(L).load(n,function(c,a,b){q(a==="error"?"Request unsuccessful: "+b.statusText:this)})};d.next=function(){if(!H){g=g<i.length-1?g+1:0;d.load()}};d.prev=function(){if(!H){g=g?g-1:i.length-1;d.load()}};d.close=function(){if(w&&!P){P=f;w=b;y(jb,a.onCleanup);n.unbind("."+j+" ."+Q);A.fadeTo("fast",0);h.stop().fadeTo("fast",0,function(){y(R);k.remove();h.add(A).css({opacity:1,cursor:r}).hide();try{X.focus()}catch(c){}setTimeout(function(){P=b;y(fb,a.onClosed)},1)})}};d.element=function(){return c(l)};d.settings=ib;c(d.init)})(jQuery,this);
$.postJSON = function(url, data, callback) {
	$.post(url, data, callback, "json");
};
$.fn.reverse = [].reverse;

var hw = {
	votepath : 'gallery/addvote/',
	updatepath : 'gallery/updateimage/',
	deletepath : 'gallery/deleteimage/',
	tagpath : 'gallery/tagphoto/',	
	addtofavorites : 'gallery/addtofavorites/',
	deletefavorite : 'gallery/deletefavorite/',
	ajaximages : 'gallery/ajaximages/',
	captcha_url: 'captcha/captchastring/comment/',
	appendURL : null,
	formAddPath: WWW_DIR_JAVASCRIPT,		
	myTimer : false,
	fetchingInfo: false,	
	
	setPath : function (path)
	{		
		this.formAddPath = path;
	},
	
	getPath : function(path)
	{		
		return this.formAddPath;
	},
		
	vote : function (photo,score)
	{
		var pdata = {
				photo	:photo,
				score		: score				
		}

		$.postJSON(this.formAddPath + this.votepath, pdata , function(data){	
			if (data.error == 'false')
			{	
				$('#vote-content').html(data.result); 
			} 
           return true;	          
		});		
	},
	
	tagphoto : function (photoid)
	{
		var pdata = {
				photo	:photoid,
				tags	:$('#IDtagsPhoto').val()				
		}

		$.postJSON(this.formAddPath + this.tagpath, pdata , function(data){	
			if (data.error == 'false')
			{	
				 $('#tags-container').html(data.result);
			} 
           return true;	          
		});	
		return false;	
	},
	
	addCheck : function (timestamp,pid)
	{
	    
	    $('#CommentButtomStore').attr("disabled","disabled");
	    var originalLabel = $('#CommentButtomStore').val();
	    $('#CommentButtomStore').val("Working...");
	    
	    var formAddPath = this.formAddPath;
	    
		$.getJSON(this.formAddPath + this.captcha_url+timestamp, function(data) {	                
			            
            var pdata = {				
				Name	     :$('#IDName').val(),				
				CommentBody  :$('#IDCommentBody').val()								
		    }		    
		    pdata["captcha_"+data.result] = timestamp;
		    
            $.postJSON(formAddPath + 'gallery/addcomment/'+pid, pdata , function(data) {                
                $('.error-list').remove();
                $('.ok').remove(); 
                
                if (data.error == 'true') {
                    $('.comment-form').prepend(data.status);
                } else { 
                    $('#comments-list').html(data.comments);
                    $('.comment-form').prepend(data.status);
                    $('#com_'+data.id).fadeIn(1500);
                    $('#IDName').val('');
                    $('#IDCommentBody').val('');
                }          
                
                $('#CommentButtomStore').removeAttr('disabled');
                $('#CommentButtomStore').val(originalLabel);
                
            });	            	
            
		});		  		
		return false;	
	},
	
	updatePhoto : function(photo_id){
	    var pdata = {
				title	  : $('#PhotoTitle_'+photo_id).val(),
				keywords  : $('#PhotoKeyword_'+photo_id).val(),				
				caption	  : $('#PhotoDescription_'+photo_id).val(),				
				anaglyph  : $('#PhotoAnaglyph_'+photo_id).attr('checked'),
				approved  : $('#PhotoApproved_'+photo_id).attr('checked')				
		}
		$('#image_status_'+photo_id).html('Updating...');
		$('#image_status_'+photo_id).removeClass('ok');
        $.postJSON(this.formAddPath + this.updatepath+photo_id, pdata , function(data){	
			if (data.error == 'false')
			{	
				$('#image_status_'+photo_id).html(data.result); 
				$('#image_status_'+photo_id).addClass('ok');
				
			} 
           return true;	          
		});		 
	},
	
	deletePhoto : function(photo_id){
	    
        $.postJSON(this.formAddPath + this.deletepath+photo_id, {} , function(data){	
			if (data.error == 'false')
			{	
				$('#image_thumb_'+photo_id).fadeOut();				
			} 
                     
		});		
		
		return false;	
	},
	
	confirm : function(question){	    
       return confirm(question);
	},
	
	setAppendURL : function(appendURLPar){
	    this.appendURL = appendURLPar;
	},
	
	getimages : function(url,direction) {	
	    	    	  
	   var appendUrlToUser = this.appendURL;
	   var ajaxImagesURL = this.ajaximages;
	   var urlmain = this.formAddPath;
	   
	   $('#ajax-navigator-content').addClass("ajax-loading-items");	
	   $('#images-ajax-container').hide();
	   $('.right-ajax').hide();
	   $('.left-ajax').hide();
	   
       $.getJSON(url + "/(direction)/"+direction, {} , function(data) {	
            
            $('#ajax-navigator-content').removeClass('ajax-loading-items'); 
            $('#images-ajax-container').show();
    	    $('.right-ajax').show();
    	    $('.left-ajax').show();
	   
            if (data.error != 'true'){	
                 
                 if (data.has_more_images == 'true') {                     
                     $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + data.left_img_pid + appendUrlToUser);
                     $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + data.right_img_pid + appendUrlToUser);                     	
			         $('#images-ajax-container').html(data.result);	
			         $('.right-ajax').show();
			         $('.left-ajax').show();
                 } else {                                        
                    
                     if (direction == 'left') { 
                         $('.left-ajax').hide(); 
                         $('.right-ajax').show();
                     } else {
                         $('.right-ajax').hide();
                         $('.left-ajax').show(); 
                     }
                     
                     var dif = data.images_found;
                     
                     if (data.images_found == 5) {  
                            $('#images-ajax-container').html(data.result);	
                            
                            if (direction == 'right') {
                                $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + data.left_img_pid + appendUrlToUser);
                            } else {
                                $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + data.right_img_pid + appendUrlToUser);
                            }
                            
                     } else if (direction == 'left') { 
                           
                           jQuery.each($('#images-ajax-container div.image-thumb').reverse(), function(i, val) {                               
                               if (dif > 0  ){ 
                                   $(this).remove();
                                   dif--;
                               }                               
                           });                            
                           $('#images-ajax-container').prepend(data.result);
                           $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + data.left_img_pid + appendUrlToUser);
                           $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + $('#images-ajax-container div.image-thumb:last a').attr('rel') + appendUrlToUser);
                           
                     } else if (direction == 'right') {       
                            
                            jQuery.each($('#images-ajax-container div.image-thumb'), function(i, val) {
                                  if (dif > 0  ){ 
                                       $(this).remove();
                                       dif--;
                                   }
                            });                           
                                          
                           $('#images-ajax-container').append(data.result);
                           $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + data.right_img_pid + appendUrlToUser);
                           $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + $('#images-ajax-container div.image-thumb a').attr('rel') + appendUrlToUser);                                                     
                           
                     } 
                 }	            
            }
		});			
		return false;	
	},
	
	addToFavorites : function(pid)
	{
		$.getJSON(this.formAddPath + this.addtofavorites+pid, {} , function(data){	
			
			$('.ad-fv').addClass('ad-fv-ok');
            	
		});
	},
	
	showImageInfo : function(img)
	{	
	    if (hw.fetchingInfo == img.attr('rel')) return;
	    
	    hw.fetchingInfo = img.attr('rel');
	    
	    clearTimeout(hw.myTimer);	    
		$.getJSON(this.formAddPath + 'gallery/showimageinfo/'+img.attr('rel'), {} , function(data) {	
		    $('#imageInfoWindow').remove()
		    img.before(data.result);
		    $('#imageInfoWindow').slideDown('fast');
		    $('#imageInfoWindow').mouseleave(function() {		    
		        hw.myTimer = setTimeout(function(){
                    $('#imageInfoWindow').fadeOut();
                    hw.fetchingInfo = false;
                },250);
		    });		    
		    $('#imageInfoWindow').mouseenter(function(){clearTimeout(hw.myTimer);});		
		});
	},
	
	deleteFavorite : function(pid)
	{
		$.getJSON(this.formAddPath + this.deletefavorite+pid, {} , function(data) {				
			$('#image_thumb_'+pid).fadeOut();            	
		});
		
		return false;
	}, 
	
	initSortBox : function(name)
	{
	    $(document).ready(function() {
            $(name+" .current-sort").mouseenter(function() {
            $(name+" .sort-box").fadeIn();
            $(name+' .choose-sort').addClass('active-sort'); 
          }).mouseleave(function() {
            $(name+' .sort-box').hide();
            $(name+' .choose-sort').removeClass('active-sort');
          });
          if ($(name+' .sort-box .selor').size() > 0){
              $(name+' .choose-sort span').text($(name+' .sort-box .selor').text());
              $(name+' .choose-sort span').addClass($(name+' .sort-box .selor').hasClass('ar') ? 'ar-ind' : 'da-ind');
          }
        });
	},

	initPalleteFilter : function(current_colors,mode,keyword)
	{	 
	   var baseURL = this.formAddPath;
	   $("#color-filter-nav .current-sort").mouseenter(function() {	
	        if (!$('#pall-comb').is('*')) {
                $.getJSON(baseURL + 'gallery/getpallete/(color)'+current_colors + '/(mode)/'+mode+ '/(keyword)/' + keyword, {} , function(data) {				
        			$('#pallete-content').html(data.result);         	
        		});      
	        }          
       })	   
	}

}

var sessionHash;

function fc(swfinstance)
{
	this.wwwDir = WWW_DIR_JAVASCRIPT;
	this.swfinstance = swfinstance;
	this.sessionHash = '';
	//Que with url files and local files total
	this.fileUploadQueTotal = 0;
	
	this.urlFileCount = 0;
	
	this.filesQue = new Array();
	
	this.hasFlashQue = false;
	this.hasUrlQue = false;
	this.hasUrlInput = false;
		
}

// Makes initial checks before uploading
fc.prototype.startUpload = function(instance)
{
	var swfinst = this.swfinstance;
	
	var thisinstance = this;
	
	
	    $('#ConvertButton').attr('disabled','disabled');
	    
		//alert(this.wwwDir);
		$.postJSON(this.wwwDir+"gallery/getsession/"+$('#AlbumIDToUpload').val(),{},function(data){			
			if (data.error == 'false')
			{	
			    if (swfinst.getStats().files_queued > 0) 			    
			    thisinstance.hasFlashQue = true;
			    	
			    // Test if all removed url files ?		    
			    if (thisinstance.filesQue.length > 0)
			    thisinstance.hasUrlQue = true;
			    
			    $('#errorsList').remove();		    
			    sessionHash = data.sessionhash;
				swfinst.addPostParam('sessionupload',data.sessionhash);
				swfinst.startUpload();				
				thisinstance.startUrlUpload();
				
			}
			else {
			    $('#ConvertButton').removeAttr('disabled');			    
			    if ($('#errorsList').size() == 0) $('#divSWFUploadUI').prepend('<div id="errorsList"></div>');
			    $('#errorsList').html(data.result);
			  
			}			
		});
	
}

fc.prototype.startUrlUpload = function()
{    
  var fileUrl = '';
  
  if (this.filesQue.length > 0) this.hasUrlInput = true;
  var instance = this;  
  
  while ((this.filesQue.length) > 0)
  {      
      fileUrl = this.filesQue.pop();
      if (fileUrl != undefined)   
      {  
          $.postJSON(this.wwwDir+"gallery/uploadurl/"+sessionHash,{'fileurl':fileUrl.url,'convertTo':fileUrl.convertTo,'fileID':fileUrl.fileID},function(data){
              instance.urlUploaded(data.fileID);
          }); 
      }
  }
}

fc.prototype.urlUploaded = function(fileID)
{
    $('#fileURUploadID'+fileID).fadeOut(2000,function(){	    
	    $(this).remove();	    
	});
    
	if (this.filesQue.length == 0)
	{
	    this.hasUrlQue = false;
        this.uploadComplete();
	}
}

fc.prototype.addRemoteFileQue = function()
{
    if ($('#URLUpload').val() != '')
    {
        this.urlFileCount++;
        var filesQue;
        filesQue = this.filesQue;
        var instance = this;
          
        $.postJSON(this.wwwDir+"fileconversion/fileuploadcontainerurl/"+this.urlFileCount,{'filename':$('#URLUpload').val()},function(data){
    			
    			if (data.error == false)  
    		    {    		        
    		            		        
        			$('#fsUploadProgress').append(data.result);	
        			filesQue[data.fileid] = {'url':data.url_file,'convertTo':$('#ConvertToURL'+data.fileid).val(),'fileID':data.fileid};        			       			     				    
        			$('#ConvertButton').removeAttr('disabled');
        			
		            $('#chooseFile').hide();
		            
		            
                	$('#cancelLinkURL'+data.fileid).click(function(){	
                		instance.cancelUploadURL(data.fileid);
                		return false;
                	});
	
		            $('#ConvertToURL'+data.fileid).change(function(){
                		instance.changeConvertTo(data.fileid,$(this).val());
                	});
		    
    		    } else {
    		            
    		        $('#fsUploadProgress').append(data.result);		        
    		        $('#progressContainer'+data.fileid).attr('class','progressContainer red');
                	$('#progressBarInProgress'+data.fileid).attr('class','progressBarError');
                	$('#fileURUploadID'+data.fileid).fadeOut(5000,function(){	    
                	    $(this).remove();	    
                	});
    		    }
    			
    		});
    }
}

fc.prototype.cancelUploadURL = function(fileID)
{
    this.filesQue.splice(fileID, 1); 
               
    $('#progressContainer'+fileID).attr('class','progressContainer red');
	$('#progressBarInProgress'+fileID).attr('class','progressBarError');
	$('#fileURUploadID'+fileID).fadeOut(2000,function(){	    
	    $(this).remove();	    
	});
	
	if (this.getLengthURL() == 0 && this.swfinstance.getStats().files_queued == 0)
	{
	    $('#ConvertButton').attr('disabled','disabled');
	    $('#chooseFile').show();
	    $('#chooseFile').html(translations.choosefilesfirst);   
	}
	
}

fc.prototype.getLengthURL = function()
{
    var count = 0;
	for (key in this.filesQue)	
	{
	    count++;
	}
	return count;
}

fc.prototype.changeConvertTo = function(id,value)
{
    this.filesQue[id].convertTo = value;
}

fc.prototype.flashQueCancel = function()
{
    if (this.getLengthURL() == 0 && this.swfinstance.getStats().files_queued == 0)
	{
        $('#ConvertButton').attr('disabled','disabled');
        $('#chooseFile').show();
        $('#chooseFile').html(translations.choosefilesfirst);
	}
}


fc.prototype.flashQueCompleted = function()
{    
	this.hasFlashQue = false;	    
	this.uploadComplete();	
}

// Upload complete
fc.prototype.uploadComplete = function()
{   
    if (this.hasFlashQue == false && this.hasUrlQue == false)
    {
       if (sessionHash != '')
       {
            var hasUrlInput = this.hasUrlInput > 0 ? '1' : '0';        
        	$.postJSON(this.wwwDir+"gallery/sessiondone/"+sessionHash+"/"+hasUrlInput,{},function(data){
        						
        		});
        	sessionHash = '';
        	
           $('#ReceiverMail').val('');
	       $('#ConvertButton').attr('disabled','disabled');
	       $('#chooseFile').show();
	       $('#chooseFile').html(translations.alluploaded);	       
       }
       
       
	
    }
};

/*!
 * jQuery UI 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI
 */
(function(c,j){function k(a){return!c(a).parents().andSelf().filter(function(){return c.curCSS(this,"visibility")==="hidden"||c.expr.filters.hidden(this)}).length}c.ui=c.ui||{};if(!c.ui.version){c.extend(c.ui,{version:"1.8.6",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,
NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});c.fn.extend({_focus:c.fn.focus,focus:function(a,b){return typeof a==="number"?this.each(function(){var d=this;setTimeout(function(){c(d).focus();b&&b.call(d)},a)}):this._focus.apply(this,arguments)},scrollParent:function(){var a;a=c.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(c.curCSS(this,
"position",1))&&/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!a.length?c(document):a},zIndex:function(a){if(a!==j)return this.css("zIndex",a);if(this.length){a=c(this[0]);for(var b;a.length&&a[0]!==document;){b=a.css("position");
if(b==="absolute"||b==="relative"||b==="fixed"){b=parseInt(a.css("zIndex"),10);if(!isNaN(b)&&b!==0)return b}a=a.parent()}}return 0},disableSelection:function(){return this.bind((c.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}});c.each(["Width","Height"],function(a,b){function d(f,g,l,m){c.each(e,function(){g-=parseFloat(c.curCSS(f,"padding"+this,true))||0;if(l)g-=parseFloat(c.curCSS(f,
"border"+this+"Width",true))||0;if(m)g-=parseFloat(c.curCSS(f,"margin"+this,true))||0});return g}var e=b==="Width"?["Left","Right"]:["Top","Bottom"],h=b.toLowerCase(),i={innerWidth:c.fn.innerWidth,innerHeight:c.fn.innerHeight,outerWidth:c.fn.outerWidth,outerHeight:c.fn.outerHeight};c.fn["inner"+b]=function(f){if(f===j)return i["inner"+b].call(this);return this.each(function(){c(this).css(h,d(this,f)+"px")})};c.fn["outer"+b]=function(f,g){if(typeof f!=="number")return i["outer"+b].call(this,f);return this.each(function(){c(this).css(h,
d(this,f,true,g)+"px")})}});c.extend(c.expr[":"],{data:function(a,b,d){return!!c.data(a,d[3])},focusable:function(a){var b=a.nodeName.toLowerCase(),d=c.attr(a,"tabindex");if("area"===b){b=a.parentNode;d=b.name;if(!a.href||!d||b.nodeName.toLowerCase()!=="map")return false;a=c("img[usemap=#"+d+"]")[0];return!!a&&k(a)}return(/input|select|textarea|button|object/.test(b)?!a.disabled:"a"==b?a.href||!isNaN(d):!isNaN(d))&&k(a)},tabbable:function(a){var b=c.attr(a,"tabindex");return(isNaN(b)||b>=0)&&c(a).is(":focusable")}});
c(function(){var a=document.body,b=a.appendChild(b=document.createElement("div"));c.extend(b.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});c.support.minHeight=b.offsetHeight===100;c.support.selectstart="onselectstart"in b;a.removeChild(b).style.display="none"});c.extend(c.ui,{plugin:{add:function(a,b,d){a=c.ui[a].prototype;for(var e in d){a.plugins[e]=a.plugins[e]||[];a.plugins[e].push([b,d[e]])}},call:function(a,b,d){if((b=a.plugins[b])&&a.element[0].parentNode)for(var e=0;e<b.length;e++)a.options[b[e][0]]&&
b[e][1].apply(a.element,d)}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(a,b){if(c(a).css("overflow")==="hidden")return false;b=b&&b==="left"?"scrollLeft":"scrollTop";var d=false;if(a[b]>0)return true;a[b]=1;d=a[b]>0;a[b]=0;return d},isOverAxis:function(a,b,d){return a>b&&a<b+d},isOver:function(a,b,d,e,h,i){return c.ui.isOverAxis(a,d,h)&&c.ui.isOverAxis(b,e,i)}})}})(jQuery);
;/*!
 * jQuery UI Widget 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Widget
 */
(function(b,j){if(b.cleanData){var k=b.cleanData;b.cleanData=function(a){for(var c=0,d;(d=a[c])!=null;c++)b(d).triggerHandler("remove");k(a)}}else{var l=b.fn.remove;b.fn.remove=function(a,c){return this.each(function(){if(!c)if(!a||b.filter(a,[this]).length)b("*",this).add([this]).each(function(){b(this).triggerHandler("remove")});return l.call(b(this),a,c)})}}b.widget=function(a,c,d){var e=a.split(".")[0],f;a=a.split(".")[1];f=e+"-"+a;if(!d){d=c;c=b.Widget}b.expr[":"][f]=function(h){return!!b.data(h,
a)};b[e]=b[e]||{};b[e][a]=function(h,g){arguments.length&&this._createWidget(h,g)};c=new c;c.options=b.extend(true,{},c.options);b[e][a].prototype=b.extend(true,c,{namespace:e,widgetName:a,widgetEventPrefix:b[e][a].prototype.widgetEventPrefix||a,widgetBaseClass:f},d);b.widget.bridge(a,b[e][a])};b.widget.bridge=function(a,c){b.fn[a]=function(d){var e=typeof d==="string",f=Array.prototype.slice.call(arguments,1),h=this;d=!e&&f.length?b.extend.apply(null,[true,d].concat(f)):d;if(e&&d.charAt(0)==="_")return h;
e?this.each(function(){var g=b.data(this,a),i=g&&b.isFunction(g[d])?g[d].apply(g,f):g;if(i!==g&&i!==j){h=i;return false}}):this.each(function(){var g=b.data(this,a);g?g.option(d||{})._init():b.data(this,a,new c(d,this))});return h}};b.Widget=function(a,c){arguments.length&&this._createWidget(a,c)};b.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:false},_createWidget:function(a,c){b.data(c,this.widgetName,this);this.element=b(c);this.options=b.extend(true,{},this.options,
this._getCreateOptions(),a);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return b.metadata&&b.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled ui-state-disabled")},
widget:function(){return this.element},option:function(a,c){var d=a;if(arguments.length===0)return b.extend({},this.options);if(typeof a==="string"){if(c===j)return this.options[a];d={};d[a]=c}this._setOptions(d);return this},_setOptions:function(a){var c=this;b.each(a,function(d,e){c._setOption(d,e)});return this},_setOption:function(a,c){this.options[a]=c;if(a==="disabled")this.widget()[c?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",c);return this},
enable:function(){return this._setOption("disabled",false)},disable:function(){return this._setOption("disabled",true)},_trigger:function(a,c,d){var e=this.options[a];c=b.Event(c);c.type=(a===this.widgetEventPrefix?a:this.widgetEventPrefix+a).toLowerCase();d=d||{};if(c.originalEvent){a=b.event.props.length;for(var f;a;){f=b.event.props[--a];c[f]=c.originalEvent[f]}}this.element.trigger(c,d);return!(b.isFunction(e)&&e.call(this.element[0],c,d)===false||c.isDefaultPrevented())}}})(jQuery);
;/*
 * jQuery UI Position 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Position
 */
(function(c){c.ui=c.ui||{};var n=/left|center|right/,o=/top|center|bottom/,t=c.fn.position,u=c.fn.offset;c.fn.position=function(b){if(!b||!b.of)return t.apply(this,arguments);b=c.extend({},b);var a=c(b.of),d=a[0],g=(b.collision||"flip").split(" "),e=b.offset?b.offset.split(" "):[0,0],h,k,j;if(d.nodeType===9){h=a.width();k=a.height();j={top:0,left:0}}else if(d.setTimeout){h=a.width();k=a.height();j={top:a.scrollTop(),left:a.scrollLeft()}}else if(d.preventDefault){b.at="left top";h=k=0;j={top:b.of.pageY,
left:b.of.pageX}}else{h=a.outerWidth();k=a.outerHeight();j=a.offset()}c.each(["my","at"],function(){var f=(b[this]||"").split(" ");if(f.length===1)f=n.test(f[0])?f.concat(["center"]):o.test(f[0])?["center"].concat(f):["center","center"];f[0]=n.test(f[0])?f[0]:"center";f[1]=o.test(f[1])?f[1]:"center";b[this]=f});if(g.length===1)g[1]=g[0];e[0]=parseInt(e[0],10)||0;if(e.length===1)e[1]=e[0];e[1]=parseInt(e[1],10)||0;if(b.at[0]==="right")j.left+=h;else if(b.at[0]==="center")j.left+=h/2;if(b.at[1]==="bottom")j.top+=
k;else if(b.at[1]==="center")j.top+=k/2;j.left+=e[0];j.top+=e[1];return this.each(function(){var f=c(this),l=f.outerWidth(),m=f.outerHeight(),p=parseInt(c.curCSS(this,"marginLeft",true))||0,q=parseInt(c.curCSS(this,"marginTop",true))||0,v=l+p+parseInt(c.curCSS(this,"marginRight",true))||0,w=m+q+parseInt(c.curCSS(this,"marginBottom",true))||0,i=c.extend({},j),r;if(b.my[0]==="right")i.left-=l;else if(b.my[0]==="center")i.left-=l/2;if(b.my[1]==="bottom")i.top-=m;else if(b.my[1]==="center")i.top-=m/2;
i.left=parseInt(i.left);i.top=parseInt(i.top);r={left:i.left-p,top:i.top-q};c.each(["left","top"],function(s,x){c.ui.position[g[s]]&&c.ui.position[g[s]][x](i,{targetWidth:h,targetHeight:k,elemWidth:l,elemHeight:m,collisionPosition:r,collisionWidth:v,collisionHeight:w,offset:e,my:b.my,at:b.at})});c.fn.bgiframe&&f.bgiframe();f.offset(c.extend(i,{using:b.using}))})};c.ui.position={fit:{left:function(b,a){var d=c(window);d=a.collisionPosition.left+a.collisionWidth-d.width()-d.scrollLeft();b.left=d>0?
b.left-d:Math.max(b.left-a.collisionPosition.left,b.left)},top:function(b,a){var d=c(window);d=a.collisionPosition.top+a.collisionHeight-d.height()-d.scrollTop();b.top=d>0?b.top-d:Math.max(b.top-a.collisionPosition.top,b.top)}},flip:{left:function(b,a){if(a.at[0]!=="center"){var d=c(window);d=a.collisionPosition.left+a.collisionWidth-d.width()-d.scrollLeft();var g=a.my[0]==="left"?-a.elemWidth:a.my[0]==="right"?a.elemWidth:0,e=a.at[0]==="left"?a.targetWidth:-a.targetWidth,h=-2*a.offset[0];b.left+=
a.collisionPosition.left<0?g+e+h:d>0?g+e+h:0}},top:function(b,a){if(a.at[1]!=="center"){var d=c(window);d=a.collisionPosition.top+a.collisionHeight-d.height()-d.scrollTop();var g=a.my[1]==="top"?-a.elemHeight:a.my[1]==="bottom"?a.elemHeight:0,e=a.at[1]==="top"?a.targetHeight:-a.targetHeight,h=-2*a.offset[1];b.top+=a.collisionPosition.top<0?g+e+h:d>0?g+e+h:0}}}};if(!c.offset.setOffset){c.offset.setOffset=function(b,a){if(/static/.test(c.curCSS(b,"position")))b.style.position="relative";var d=c(b),
g=d.offset(),e=parseInt(c.curCSS(b,"top",true),10)||0,h=parseInt(c.curCSS(b,"left",true),10)||0;g={top:a.top-g.top+e,left:a.left-g.left+h};"using"in a?a.using.call(b,g):d.css(g)};c.fn.offset=function(b){var a=this[0];if(!a||!a.ownerDocument)return null;if(b)return this.each(function(){c.offset.setOffset(this,b)});return u.call(this)}}})(jQuery);
;/*
 * jQuery UI Autocomplete 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Autocomplete
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *	jquery.ui.position.js
 */
(function(e){e.widget("ui.autocomplete",{options:{appendTo:"body",delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null},_create:function(){var a=this,b=this.element[0].ownerDocument,f;this.element.addClass("ui-autocomplete-input").attr("autocomplete","off").attr({role:"textbox","aria-autocomplete":"list","aria-haspopup":"true"}).bind("keydown.autocomplete",function(c){if(!(a.options.disabled||a.element.attr("readonly"))){f=false;var d=e.ui.keyCode;switch(c.keyCode){case d.PAGE_UP:a._move("previousPage",
c);break;case d.PAGE_DOWN:a._move("nextPage",c);break;case d.UP:a._move("previous",c);c.preventDefault();break;case d.DOWN:a._move("next",c);c.preventDefault();break;case d.ENTER:case d.NUMPAD_ENTER:if(a.menu.active){f=true;c.preventDefault()}case d.TAB:if(!a.menu.active)return;a.menu.select(c);break;case d.ESCAPE:a.element.val(a.term);a.close(c);break;default:clearTimeout(a.searching);a.searching=setTimeout(function(){if(a.term!=a.element.val()){a.selectedItem=null;a.search(null,c)}},a.options.delay);
break}}}).bind("keypress.autocomplete",function(c){if(f){f=false;c.preventDefault()}}).bind("focus.autocomplete",function(){if(!a.options.disabled){a.selectedItem=null;a.previous=a.element.val()}}).bind("blur.autocomplete",function(c){if(!a.options.disabled){clearTimeout(a.searching);a.closing=setTimeout(function(){a.close(c);a._change(c)},150)}});this._initSource();this.response=function(){return a._response.apply(a,arguments)};this.menu=e("<ul></ul>").addClass("ui-autocomplete").appendTo(e(this.options.appendTo||
"body",b)[0]).mousedown(function(c){var d=a.menu.element[0];e(c.target).closest(".ui-menu-item").length||setTimeout(function(){e(document).one("mousedown",function(g){g.target!==a.element[0]&&g.target!==d&&!e.ui.contains(d,g.target)&&a.close()})},1);setTimeout(function(){clearTimeout(a.closing)},13)}).menu({focus:function(c,d){d=d.item.data("item.autocomplete");false!==a._trigger("focus",c,{item:d})&&/^key/.test(c.originalEvent.type)&&a.element.val(d.value)},selected:function(c,d){d=d.item.data("item.autocomplete");
var g=a.previous;if(a.element[0]!==b.activeElement){a.element.focus();a.previous=g;setTimeout(function(){a.previous=g},1)}false!==a._trigger("select",c,{item:d})&&a.element.val(d.value);a.term=a.element.val();a.close(c);a.selectedItem=d},blur:function(){a.menu.element.is(":visible")&&a.element.val()!==a.term&&a.element.val(a.term)}}).zIndex(this.element.zIndex()+1).css({top:0,left:0}).hide().data("menu");e.fn.bgiframe&&this.menu.element.bgiframe()},destroy:function(){this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete").removeAttr("role").removeAttr("aria-autocomplete").removeAttr("aria-haspopup");
this.menu.element.remove();e.Widget.prototype.destroy.call(this)},_setOption:function(a,b){e.Widget.prototype._setOption.apply(this,arguments);a==="source"&&this._initSource();if(a==="appendTo")this.menu.element.appendTo(e(b||"body",this.element[0].ownerDocument)[0])},_initSource:function(){var a=this,b,f;if(e.isArray(this.options.source)){b=this.options.source;this.source=function(c,d){d(e.ui.autocomplete.filter(b,c.term))}}else if(typeof this.options.source==="string"){f=this.options.source;this.source=
function(c,d){a.xhr&&a.xhr.abort();a.xhr=e.getJSON(f,c,function(g,i,h){h===a.xhr&&d(g);a.xhr=null})}}else this.source=this.options.source},search:function(a,b){a=a!=null?a:this.element.val();this.term=this.element.val();if(a.length<this.options.minLength)return this.close(b);clearTimeout(this.closing);if(this._trigger("search",b)!==false)return this._search(a)},_search:function(a){this.element.addClass("ui-autocomplete-loading");this.source({term:a},this.response)},_response:function(a){if(a&&a.length){a=
this._normalize(a);this._suggest(a);this._trigger("open")}else this.close();this.element.removeClass("ui-autocomplete-loading")},close:function(a){clearTimeout(this.closing);if(this.menu.element.is(":visible")){this._trigger("close",a);this.menu.element.hide();this.menu.deactivate()}},_change:function(a){this.previous!==this.element.val()&&this._trigger("change",a,{item:this.selectedItem})},_normalize:function(a){if(a.length&&a[0].label&&a[0].value)return a;return e.map(a,function(b){if(typeof b===
"string")return{label:b,value:b};return e.extend({label:b.label||b.value,value:b.value||b.label},b)})},_suggest:function(a){this._renderMenu(this.menu.element.empty().zIndex(this.element.zIndex()+1),a);this.menu.deactivate();this.menu.refresh();this.menu.element.show().position(e.extend({of:this.element},this.options.position));this._resizeMenu()},_resizeMenu:function(){var a=this.menu.element;a.outerWidth(Math.max(a.width("").outerWidth(),this.element.outerWidth()))},_renderMenu:function(a,b){var f=
this;e.each(b,function(c,d){f._renderItem(a,d)})},_renderItem:function(a,b){return e("<li></li>").data("item.autocomplete",b).append(e("<a></a>").text(b.label)).appendTo(a)},_move:function(a,b){if(this.menu.element.is(":visible"))if(this.menu.first()&&/^previous/.test(a)||this.menu.last()&&/^next/.test(a)){this.element.val(this.term);this.menu.deactivate()}else this.menu[a](b);else this.search(null,b)},widget:function(){return this.menu.element}});e.extend(e.ui.autocomplete,{escapeRegex:function(a){return a.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,
"\\$&")},filter:function(a,b){var f=new RegExp(e.ui.autocomplete.escapeRegex(b),"i");return e.grep(a,function(c){return f.test(c.label||c.value||c)})}})})(jQuery);
(function(e){e.widget("ui.menu",{_create:function(){var a=this;this.element.addClass("ui-menu ui-widget ui-widget-content ui-corner-all").attr({role:"listbox","aria-activedescendant":"ui-active-menuitem"}).click(function(b){if(e(b.target).closest(".ui-menu-item a").length){b.preventDefault();a.select(b)}});this.refresh()},refresh:function(){var a=this;this.element.children("li:not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","menuitem").children("a").addClass("ui-corner-all").attr("tabindex",
-1).mouseenter(function(b){a.activate(b,e(this).parent())}).mouseleave(function(){a.deactivate()})},activate:function(a,b){this.deactivate();if(this.hasScroll()){var f=b.offset().top-this.element.offset().top,c=this.element.attr("scrollTop"),d=this.element.height();if(f<0)this.element.attr("scrollTop",c+f);else f>=d&&this.element.attr("scrollTop",c+f-d+b.height())}this.active=b.eq(0).children("a").addClass("ui-state-hover").attr("id","ui-active-menuitem").end();this._trigger("focus",a,{item:b})},
deactivate:function(){if(this.active){this.active.children("a").removeClass("ui-state-hover").removeAttr("id");this._trigger("blur");this.active=null}},next:function(a){this.move("next",".ui-menu-item:first",a)},previous:function(a){this.move("prev",".ui-menu-item:last",a)},first:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},last:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},move:function(a,b,f){if(this.active){a=this.active[a+"All"](".ui-menu-item").eq(0);
a.length?this.activate(f,a):this.activate(f,this.element.children(b))}else this.activate(f,this.element.children(b))},nextPage:function(a){if(this.hasScroll())if(!this.active||this.last())this.activate(a,this.element.children(".ui-menu-item:first"));else{var b=this.active.offset().top,f=this.element.height(),c=this.element.children(".ui-menu-item").filter(function(){var d=e(this).offset().top-b-f+e(this).height();return d<10&&d>-10});c.length||(c=this.element.children(".ui-menu-item:last"));this.activate(a,
c)}else this.activate(a,this.element.children(".ui-menu-item").filter(!this.active||this.last()?":first":":last"))},previousPage:function(a){if(this.hasScroll())if(!this.active||this.first())this.activate(a,this.element.children(".ui-menu-item:last"));else{var b=this.active.offset().top,f=this.element.height();result=this.element.children(".ui-menu-item").filter(function(){var c=e(this).offset().top-b+f-e(this).height();return c<10&&c>-10});result.length||(result=this.element.children(".ui-menu-item:first"));
this.activate(a,result)}else this.activate(a,this.element.children(".ui-menu-item").filter(!this.active||this.first()?":last":":first"))},hasScroll:function(){return this.element.height()<this.element.attr("scrollHeight")},select:function(a){this._trigger("selected",a,{item:this.active})}})})(jQuery);
;

var cache = {},
		lastXhr;
				
$(document).ready(function() {
    $('#searchtext').focus();       
	$( "#searchtext" ).autocomplete({
		minLength: 2,		
		source: function( request, response ) {
			var term = request.term;
			if ( term in cache ) {
				response( cache[ term ] );
				return;
			}
			lastXhr = $.getJSON( WWW_DIR_JAVASCRIPT + 'gallery/suggest/'+request.term, function( data, status, xhr ) {
				cache[ term ] = data;
				if ( xhr === lastXhr ) {
					response( data );
				}
			});
		}
	});	
});