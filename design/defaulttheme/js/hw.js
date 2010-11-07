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
	
	addCheck : function (timestamp)
	{
	    
	    $('#CommentButtomStore').attr("disabled","disabled");
	    $('#CommentButtomStore').val("Working...");
		$.getJSON(this.formAddPath + this.captcha_url+timestamp, function(data) {	                
			var input = $(document.createElement('input'));
            input.attr("name","captcha_"+data.result);
            input.attr("value",timestamp);
            input.attr("type","hidden");
            input.attr("id","id_captcha_code");
            $('#comment_form_data').prepend(input);                 
            document.comment_form.submit();
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
}