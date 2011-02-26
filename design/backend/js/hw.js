/*	ColorBox v1.3.5b - a full featured, light-weight, customizable lightbox based on jQuery 1.3 */
(function(c){function r(b,d){d=d==="x"?m.width():m.height();return typeof b==="string"?Math.round(b.match(/%/)?d/100*parseInt(b,10):parseInt(b,10)):b}function N(b){b=c.isFunction(b)?b.call(i):b;return a.photo||b.match(/\.(gif|png|jpg|jpeg|bmp)(?:\?([^#]*))?(?:#(\.*))?$/i)}function Z(){for(var b in a)if(c.isFunction(a[b])&&b.substring(0,2)!=="on")a[b]=a[b].call(i)}function $(b){i=b;a=c(i).data(q);Z();var d=a.rel||i.rel;if(d&&d!=="nofollow"){h=c(".cboxElement").filter(function(){return(c(this).data(q).rel|| this.rel)===d});j=h.index(i);if(j<0){h=h.add(i);j=h.length-1}}else{h=c(i);j=0}if(!B){C=B=n;O=i;O.blur();c().bind("keydown.cbox_close",function(e){if(e.keyCode===27){e.preventDefault();f.close()}}).bind("keydown.cbox_arrows",function(e){if(h.length>1)if(e.keyCode===37){e.preventDefault();D.click()}else if(e.keyCode===39){e.preventDefault();E.click()}});a.overlayClose&&s.css({cursor:"pointer"}).one("click",f.close);c.event.trigger(aa);a.onOpen&&a.onOpen.call(i);s.css({opacity:a.opacity}).show();a.w= r(a.initialWidth,"x");a.h=r(a.initialHeight,"y");f.position(0);P&&m.bind("resize.cboxie6 scroll.cboxie6",function(){s.css({width:m.width(),height:m.height(),top:m.scrollTop(),left:m.scrollLeft()})}).trigger("scroll.cboxie6")}Q.add(D).add(E).add(t).add(H).hide();R.html(a.close).show();f.slideshow();f.load()}var q="colorbox",F="hover",n=true,f,x=!c.support.opacity,P=x&&!window.XMLHttpRequest,aa="cbox_open",I="cbox_load",S="cbox_complete",T="resize.cbox_resize",s,k,u,p,U,V,W,X,h,m,l,J,K,L,H,Q,t,E,D, R,y,z,v,w,i,O,j,a,B,C,Y={transition:"elastic",speed:350,width:false,height:false,innerWidth:false,innerHeight:false,initialWidth:"400",initialHeight:"400",maxWidth:false,maxHeight:false,scalePhotos:n,scrolling:n,inline:false,html:false,iframe:false,photo:false,href:false,title:false,rel:false,opacity:0.9,preloading:n,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",open:false,overlayClose:n,slideshow:false,slideshowAuto:n,slideshowSpeed:2500,slideshowStart:"start slideshow", slideshowStop:"stop slideshow",onOpen:false,onLoad:false,onComplete:false,onCleanup:false,onClosed:false};f=c.fn.colorbox=function(b,d){var e=this;if(!e.length)if(e.selector===""){e=c(e).data(q,Y);b.open=n}else return this;e.each(function(){var g=c.extend({},c(this).data(q)?c(this).data(q):Y,b);c(this).data(q,g).addClass("cboxElement");if(d)c(this).data(q).onComplete=d});b&&b.open&&$(e);return this};f.init=function(){function b(d){return c('<div id="cbox'+d+'"/>')}m=c(window);k=c('<div id="colorbox"/>'); s=b("Overlay").hide();u=b("Wrapper");p=b("Content").append(l=b("LoadedContent").css({width:0,height:0}),K=b("LoadingOverlay"),L=b("LoadingGraphic"),H=b("Title"),Q=b("Current"),t=b("Slideshow"),E=b("Next"),D=b("Previous"),R=b("Close"));u.append(c("<div/>").append(b("TopLeft"),U=b("TopCenter"),b("TopRight")),c("<div/>").append(V=b("MiddleLeft"),p,W=b("MiddleRight")),c("<div/>").append(b("BottomLeft"),X=b("BottomCenter"),b("BottomRight"))).children().children().css({"float":"left"});J=c("<div style='position:absolute; top:0; left:0; width:9999px; height:0;'/>"); c("body").prepend(s,k.append(u,J));if(x){k.addClass("cboxIE");P&&s.css("position","absolute")}p.children().bind("mouseover mouseout",function(){c(this).toggleClass(F)}).addClass(F);y=U.height()+X.height()+p.outerHeight(n)-p.height();z=V.width()+W.width()+p.outerWidth(n)-p.width();v=l.outerHeight(n);w=l.outerWidth(n);k.css({"padding-bottom":y,"padding-right":z}).hide();E.click(f.next);D.click(f.prev);R.click(f.close);p.children().removeClass(F);c(".cboxElement").live("click",function(d){if(d.button!== 0&&typeof d.button!=="undefined")return n;else{$(this);return false}})};f.position=function(b,d){function e(A){U[0].style.width=X[0].style.width=p[0].style.width=A.style.width;L[0].style.height=K[0].style.height=p[0].style.height=V[0].style.height=W[0].style.height=A.style.height}var g=m.height();g=Math.max(g-a.h-v-y,0)/2+m.scrollTop();var o=Math.max(document.documentElement.clientWidth-a.w-w-z,0)/2+m.scrollLeft();b=k.width()===a.w+w&&k.height()===a.h+v?0:b;u[0].style.width=u[0].style.height="9999px"; k.dequeue().animate({width:a.w+w,height:a.h+v,top:g,left:o},{duration:b,complete:function(){e(this);C=false;u[0].style.width=a.w+w+z+"px";u[0].style.height=a.h+v+y+"px";d&&d()},step:function(){e(this)}})};f.resize=function(b){function d(){a.w=a.w||l.width();a.w=a.mw&&a.mw<a.w?a.mw:a.w;return a.w}function e(){a.h=a.h||l.height();a.h=a.mh&&a.mh<a.h?a.mh:a.h;return a.h}function g(G){f.position(G,function(){if(B){if(x){A&&l.fadeIn(100);k[0].style.removeAttribute("filter")}if(a.iframe)l.append("<iframe id='cboxIframe'"+ (a.scrolling?" ":"scrolling='no'")+" name='iframe_"+(new Date).getTime()+"' frameborder=0 src='"+(a.href||i.href)+"' "+(x?"allowtransparency='true'":"")+" />");l.show();H.html(a.title||i.title);H.show();if(h.length>1){Q.html(a.current.replace(/\{current\}/,j+1).replace(/\{total\}/,h.length)).show();E.html(a.next).show();D.html(a.previous).show();a.slideshow&&t.show()}K.hide();L.hide();c.event.trigger(S);a.onComplete&&a.onComplete.call(i);a.transition==="fade"&&k.fadeTo(M,1,function(){x&&k[0].style.removeAttribute("filter")}); m.bind(T,function(){f.position(0)})}})}if(B){var o,A,M=a.transition==="none"?0:a.speed;m.unbind(T);if(b){l.remove();l=c('<div id="cboxLoadedContent"/>').html(b);l.hide().appendTo(J).css({width:d(),overflow:a.scrolling?"auto":"hidden"}).css({height:e()}).prependTo(p);c("#cboxPhoto").css({cssFloat:"none"});P&&c("select:not(#colorbox select)").filter(function(){return this.style.visibility!=="hidden"}).css({visibility:"hidden"}).one("cbox_cleanup",function(){this.style.visibility="inherit"});a.transition=== "fade"&&k.fadeTo(M,0,function(){g(0)})||g(M);if(a.preloading&&h.length>1){b=j>0?h[j-1]:h[h.length-1];o=j<h.length-1?h[j+1]:h[0];o=c(o).data(q).href||o.href;b=c(b).data(q).href||b.href;N(o)&&c("<img />").attr("src",o);N(b)&&c("<img />").attr("src",b)}}else setTimeout(function(){var G=l.wrapInner("<div style='overflow:auto'></div>").children();a.h=G.height();l.css({height:a.h});G.replaceWith(G.children());f.position(M)},1)}};f.load=function(){var b,d,e,g=f.resize;C=n;i=h[j];a=c(i).data(q);Z();c.event.trigger(I); a.onLoad&&a.onLoad.call(i);a.h=a.height?r(a.height,"y")-v-y:a.innerHeight?r(a.innerHeight,"y"):false;a.w=a.width?r(a.width,"x")-w-z:a.innerWidth?r(a.innerWidth,"x"):false;a.mw=a.w;a.mh=a.h;if(a.maxWidth){a.mw=r(a.maxWidth,"x")-w-z;a.mw=a.w&&a.w<a.mw?a.w:a.mw}if(a.maxHeight){a.mh=r(a.maxHeight,"y")-v-y;a.mh=a.h&&a.h<a.mh?a.h:a.mh}b=a.href||c(i).attr("href");K.show();L.show();if(a.inline){c('<div id="cboxInlineTemp" />').hide().insertBefore(c(b)[0]).bind(I+" cbox_cleanup",function(){c(this).replaceWith(l.children())}); g(c(b))}else if(a.iframe)g(" ");else if(a.html)g(a.html);else if(N(b)){d=new Image;d.onload=function(){var o;d.onload=null;d.id="cboxPhoto";c(d).css({margin:"auto",border:"none",display:"block",cssFloat:"left"});if(a.scalePhotos){e=function(){d.height-=d.height*o;d.width-=d.width*o};if(a.mw&&d.width>a.mw){o=(d.width-a.mw)/d.width;e()}if(a.mh&&d.height>a.mh){o=(d.height-a.mh)/d.height;e()}}if(a.h)d.style.marginTop=Math.max(a.h-d.height,0)/2+"px";g(d);h.length>1&&c(d).css({cursor:"pointer"}).click(f.next); if(x)d.style.msInterpolationMode="bicubic"};d.src=b}else c("<div />").appendTo(J).load(b,function(o,A){A==="success"?g(this):g(c("<p>Request unsuccessful.</p>"))})};f.next=function(){if(!C){j=j<h.length-1?j+1:0;f.load()}};f.prev=function(){if(!C){j=j>0?j-1:h.length-1;f.load()}};f.slideshow=function(){function b(){t.text(a.slideshowStop).bind(S,function(){e=setTimeout(f.next,a.slideshowSpeed)}).bind(I,function(){clearTimeout(e)}).one("click",function(){d();c(this).removeClass(F)});k.removeClass(g+ "off").addClass(g+"on")}var d,e,g="cboxSlideshow_";t.bind("cbox_closed",function(){t.unbind();clearTimeout(e);k.removeClass(g+"off "+g+"on")});d=function(){clearTimeout(e);t.text(a.slideshowStart).unbind(S+" "+I).one("click",function(){b();e=setTimeout(f.next,a.slideshowSpeed);c(this).removeClass(F)});k.removeClass(g+"on").addClass(g+"off")};if(a.slideshow&&h.length>1)a.slideshowAuto?b():d()};f.close=function(){c.event.trigger("cbox_cleanup");a.onCleanup&&a.onCleanup.call(i);B=false;c().unbind("keydown.cbox_close keydown.cbox_arrows"); m.unbind(T+" resize.cboxie6 scroll.cboxie6");s.css({cursor:"auto"}).fadeOut("fast");k.stop(n,false).fadeOut("fast",function(){c("#colorbox iframe").attr("src","about:blank");l.remove();k.css({opacity:1});try{O.focus()}catch(b){}c.event.trigger("cbox_closed");a.onClosed&&a.onClosed.call(i)})};f.element=function(){return c(i)};f.settings=Y;c(f.init)})(jQuery);
$.postJSON = function(url, data, callback) {
	$.post(url, data, callback, "json");
};

var hw = {
	votepath : 'gallery/addvote/',
	votedeductpath : 'gallery/deductvote/',
	updatepath : 'gallery/updateimage/',
	deletepath : 'gallery/deleteimage/',
	tagpath : 'gallery/tagphoto/',
	captcha_url: 'captcha/captchastring/comment/',
	deletecommentpath: 'gallery/deletecomment/',
	formAddPath: WWW_DIR_JAVASCRIPT,		
	appendURL : null,
		
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
	
	deductvote : function (photo)
	{
		$.postJSON(this.formAddPath + this.votedeductpath + photo, function(data){	
			if (data.error == 'false')
			{	
				$('#vote-content').html(data.result); 
			} 
           return true;	          
		});		
	},
	
	setAppendURL : function(appendURLPar){
	    this.appendURL = appendURLPar;
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
	
	deleteComment : function (comment_id)
	{		
		$.postJSON(this.formAddPath + this.deletecommentpath + comment_id, function(data){	
		   if (data.error == 'false')
		   {	
				 $('#comment_row_id_'+comment_id).fadeOut();
		   } 
           return true;	          
		});	
		return false;	
	},
	
	getalbumcacheinfo : function (album_id)
	{		
		$.postJSON(this.formAddPath + 'system/albumcacheinfo/'+album_id,  function(data) {			    
		   $('#information-block-album').html(data.result);			
           return true;	          
		});	
		return false;	
	},
	
	getcategorycacheinfo : function (category_id)
	{		
		$.postJSON(this.formAddPath + 'system/categorycacheinfo/'+category_id,  function(data) {			    
		   $('#information-block-category').html(data.result);			
           return true;	          
		});	
		return false;	
	},
	
	clearimagecache : function (image_id)
	{		
		$.postJSON(this.formAddPath + 'system/clearimagecache/'+image_id,  function(data) {			    
		   alert("Cache cleared!");			
           return true;	          
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
	    
	    if (confirm('Are you sure?')) {
            $.postJSON(this.formAddPath + this.deletepath+photo_id, {} , function(data){	
    			if (data.error == 'false')
    			{	
    				$('#image_thumb_'+photo_id).fadeOut();				
    			} 
                         
    		});		
	    }
		return false;	
	},
	
	deletePhotoQuick : function(photo_id,message){
	    
	    if (confirm('Are you sure?')) {
            $.postJSON(this.formAddPath + this.deletepath+photo_id, {} , function(data){	
    			if (data.error == 'false')
    			{	
    				alert(message);			
    			} 
                         
    		});		
	    }
		return false;	
	},
	
	confirm : function(question){	    
       return confirm(question);
	},
	
	getimages : function(url,direction) {	
        $.getJSON(url + "/(direction)/"+direction, {} , function(data){	
            if (data.error != 'true')			
			$('#ajax-navigator-content').html(data.result);	
		});			
		return false;	
	},
	
	expandBlock : function(idBlock){
	    
	    $('.duplicates-row').hide();	    
	    $('#details-block-'+idBlock).fadeIn();
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

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};