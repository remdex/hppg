<div class="image-full">
<div class="image-full-content">
<h1><?=htmlspecialchars($image->name_user)?></h1>
<div class="navigator float-break">

<? switch ($mode) {
    
	case 'album': ?>		
    	<? 
    	if (count($imagesLeft) > 0) :   
    	$next_image = current($imagesLeft); 
    	$imagesLeft = array_reverse($imagesLeft);    	
    	?>
        <a class="left-image" href="<?=$next_image->url_path.$urlAppend?>">&laquo; previous image</a>
        <?
        endif;
        $pageAppend = $page > 1 ? '/(page)/'.$page : '';
        ?>        
        <a href="<? echo erLhcoreClassModelGalleryAlbum::fetch($image->aid)->url_path,$pageAppend,$urlAppend?>">&laquo; return to thumbnails &raquo;</a>        
        <? 
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight); 
        ?>
        <a class="right-image" href="<?=$prev_image->url_path.$urlAppend?>">next image &raquo;</a> 
        <? endif;
        $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
        ?>
	<?break;

	case 'lastuploads': ?>		
    	<? 
    	$urlAppend = '/(mode)/lastuploads';
    	$imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 2,'sort' => 'ctime ASC','filtergt' => array('pid' => $image->pid)));    	
    	if (count($imagesLeft) > 0) :    	
    	$next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft);        	
    	?>
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/lastuploads">&laquo; previous image</a>
        <? 
        endif;
        $page = ceil(erLhcoreClassModelGalleryImage::getImageCount(array('filtergt' => array('pid' => $image->pid)))/20);
        $pageAppend = $page > 1 ? '/(page)/'.$page : '';
        ?>        
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploads')?><?=$pageAppend?>">&laquo; return to thumbnails &raquo;</a>        
        <? 
        $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 2,'sort' => 'ctime DESC','filterlt' => array('pid' => $image->pid)));
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);
        ?>      
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/lastuploads">next image &raquo;</a>
        <? endif; 
        $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
        ?>
	<?break;
	
	case 'search':	    
	//$urlAppend = '/(mode)/search/(keyword)/'.urlencode($keyword);
	//$imagesLeft = array();		 			
	//$totalPhotos = erLhcoreClassGallery::searchSphinx(array('SearchLimit' => 2,'keyword' => $keyword,'sort' => '@id ASC','filtergt' => array('pid' => $image->pid)));		
	if (count($imagesLeft) > 0) :
    $next_image = current($imagesLeft);
    $imagesLeft = array_reverse($imagesLeft);             
	?>
    <a class="left-image" href="<?=$next_image->url_path?>/(mode)/search/(keyword)/<?php echo urlencode($keyword),$mode_sort_append?>" >&laquo; previous image</a>
    <? 
    endif;
	$pageAppend = $page > 1 ? '/(page)/'.$page : '';
	?>
    <a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/<?=urlencode($keyword)?><?php echo $pageAppend,$mode_sort_append?>">&laquo; return to thumbnails mode &raquo;</a>      
    <? 
	if (count($imagesRight) > 0) :
	$next_image = current($imagesRight);
	?>	
    <a class="right-image" href="<?=$next_image->url_path?>/(mode)/search/(keyword)/<?php echo urlencode($keyword),$mode_sort_append?>" >next image &raquo;</a>     
	<?endif;
	$imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	?>         
	<?break;
	
	case 'popular': ?>		
    	<?     	    	
    	$urlAppend = '/(mode)/popular';
    	$db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->gt( 'hits', $q->bindValue( $image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('hits ASC, pid ASC')
        ->limit( 2 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );        
        if (count($imagesLeft) > 0) :   
    	$next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft); 
    	?>
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/popular">&laquo; previous image</a>             
        <?  endif;   	
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE hits > :hits OR hits = :hits AND pid > :pid LIMIT 1');
        $stmt->bindValue( ':hits',$image->hits);
        $stmt->bindValue( ':pid',$image->pid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();                 
        $page = ceil(($photos+1)/20);
	    $pageAppend = $page > 1 ? '/(page)/'.$page : '';
    	?>    	
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/popular')?><?=$pageAppend?>">&laquo; return to thumbnails &raquo;</a>        
        <?   
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->lt( 'hits', $q->bindValue( $image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('hits DESC, pid DESC')
        ->limit( 2 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );        
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);         
    	?>       
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/popular">next image &raquo;</a>       
	<?endif;
	   $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	break;
	
	case 'lasthits': ?>		
    	<? 
    	$urlAppend = '/(mode)/lasthits';
    	$db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->gt( 'mtime', $q->bindValue( $image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('mtime ASC, pid ASC')
        ->limit( 2 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );        
        if (count($imagesLeft) > 0) :   
    	$next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft);    	    	          
    	?>
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/lasthits">&laquo; previous image</a>             
        <?endif; 	
            $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE mtime > :mtime OR mtime = :mtime AND pid > :pid LIMIT 1');
            $stmt->bindValue( ':mtime',$image->mtime);
            $stmt->bindValue( ':pid',$image->pid);       
            $stmt->execute();  
            $photos = $stmt->fetchColumn();         
            $page = ceil(($photos+1)/20);
    	    $pageAppend = $page > 1 ? '/(page)/'.$page : '';  
    	?>    	
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?><?=$pageAppend?>">&laquo; return to thumbnails &raquo;</a>        
        <? 
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->lt( 'mtime', $q->bindValue( $image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('mtime DESC, pid DESC')
        ->limit( 2 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);              
    	?>       
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/lasthits">next image &raquo;</a> 
	   <?endif;
	   $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	   break;
	   
	case 'lastcommented': ?>		
    	<?    	
        $urlAppend = '/(mode)/lastcommented';
    	$db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();
        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->gt( 'comtime', $q->bindValue( $image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('comtime ASC, pid ASC')
        ->limit( 2 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                
        if (count($imagesLeft) > 0) :
        $next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft);
    	?>
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/lastcommented">&laquo; previous image</a>             
        <?endif;     	
        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE comtime > :comtime OR comtime = :comtime AND pid > :pid LIMIT 1');
        $stmt->bindValue( ':comtime',$image->comtime);
        $stmt->bindValue( ':pid',$image->pid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();
        $page = ceil(($photos+1)/20);
	    $pageAppend = $page > 1 ? '/(page)/'.$page : ''; 
    	?>    	
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/lastcommented')?><?=$pageAppend?>">&laquo; return to thumbnails &raquo;</a>        
        <?   
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->lt( 'comtime', $q->bindValue( $image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('comtime DESC, pid DESC')
        ->limit( 2 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);
    	?>       
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/lastcommented">next image &raquo;</a> 
	   <?endif;
	   $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	   break;
	
	case 'toprated': ?>		
    	<?  
    	$urlAppend = '/(mode)/toprated';
    	$db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();        
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->gt( 'pic_rating', $q->bindValue( $image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $image->votes ) ).' OR '.
         $q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ))
        ->orderBy('pic_rating ASC, votes ASC, pid ASC')
        ->limit( 2 );
        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );                
        if (count($imagesLeft) > 0) :
        $next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft);
        ?>                  	
       <a class="left-image" href="<?=$next_image->url_path?>/(mode)/toprated">&laquo; previous image</a>                            
       <?php endif;    
       $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid > :pid');
       $stmt->bindValue( ':pic_rating',$image->pic_rating);       
       $stmt->bindValue( ':votes',$image->votes);       
       $stmt->bindValue( ':pid',$image->pid);       
       $stmt->execute();
       $photos = $stmt->fetchColumn();         
       $page = ceil(($photos+1)/20);
	   $pageAppend = $page > 1 ? '/(page)/'.$page : '';                     
       ?>            
       <a href="<?=erLhcoreClassDesign::baseurl('gallery/toprated')?><?=$pageAppend?>">&laquo; return to thumbnails &raquo;</a>                
       <?  
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->lt( 'pic_rating', $q->bindValue( $image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $image->votes ) ).' OR '.
         $q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ))
        ->orderBy('pic_rating DESC, votes DESC, pid DESC')
        ->limit( 2 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);
        ?>        
       <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/toprated">next image &raquo;</a>     
	<?endif;
	   $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	break;
	
	default:
		break;
}
?>



</div>

<br />

<div class="img">

<div class="right" id="id_window_right">
<?if ($image->owner_id == 1 || $image->owner_id == 0) : ?>
<form onsubmit="return hw.tagphoto(<?=$image->pid;?>)">
<div class="act-blc" id="tags-container">
<input type="text" id="IDtagsPhoto" value="" class="inputfield" title="Enter tags separated by space" /> <input type="button" onclick="hw.tagphoto(<?=$image->pid;?>)" class="default-button" value="tag it!" /> <i>Tag this photo</i>
</div>
</form>
<?endif;?>
</div>

<a id="photo_full" href="<?=erLhcoreClassDesign::imagePath($image->filepath.$image->filename)?>"><img src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="Click to see fullsize"/></a>
<?php if( $image->caption != '') : ?>
<div class="float-break"><?=htmlspecialchars($image->caption)?></div>
<?endif;?>
</div>


<div class="navigator-ajax float-break" id="ajax-navigator-content">
<?php
if (count($imagesLeft) > 0) :
reset($imagesAjax);
$lastImages = current($imagesAjax);
?>
<div class="left-ajax">
<a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$lastImages->pid?><?=$urlAppend?>"></a>
</div>
<? 
endif;
foreach ($imagesAjax as $key => $item) : ?>
    <div class="image-thumb thumb-pic-small">
        <div class="thumb-pic">
            <a href="<?=$item->url_path?><?=isset($urlAppend) ? $urlAppend : ''?>"><img title="See full size" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename))?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>
        </div>
        <div class="thumb-attr">
        <ul>            
            <li><h3><?=($title = $item->name_user) == '' ? 'preview version' : $title;?></h3></li>
        </ul>
        </div>
    </div>   
<?endforeach; 
if (count($imagesRight) > 0) :
end($imagesAjax);
$lastImages = current($imagesAjax);
?> 
<div class="right-ajax">
<a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$lastImages->pid?><?=$urlAppend?>"></a>
</div>
<?php endif;?>
</div>


<script type="text/javascript">
$('#photo_full').each(function(index) {	
	$(this).colorbox({href:$(this).attr('href')});	
	$(this).attr('href','');
})

$('.right-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'right');
   return false;
});
$('.left-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'left');
   return false;
});
</script>



<div class="picture-details">
<h3>Picture details</h3>
<ul>
    <li>Filename: <?=htmlspecialchars($image->filename);?></li>
    <li>File Size: <?=$image->filesize_user;?></li>
    <li>Image rating<?=$image->votes > 0 ? ' ('.$image->votes.' votes)' : ''?>: <img align="absmiddle" src="<?=erLhcoreClassDesign::design('images/gallery/rating');?><?=round($image->pic_rating/2000)?>.gif" alt=""/></li>
    <li>Date added: <?=date('Y-m-d H:i:s',$image->ctime);?></li>
    <li>Dimensions: <?=$image->pwidth?>x<?=$image->pheight?></li>
    <li>Displayed: <?=$image->hits?> times</li>
    <li>URL: <a href="http://<?=$_SERVER['HTTP_HOST']?><?=$image->url_path?>">http://<?=$_SERVER['HTTP_HOST']?><?=$image->url_path?></a></li>
</ul>
</div>

<div class="picture-voting">
<h3>Rate this picture</h3>
<div id="vote-content">
    <label><input type="radio" checked="checked" value="1" name="Voting" />(1 stars)</label> <label><input type="radio" value="2" name="Voting" /> (2 stars)</label> <label><input type="radio" value="3" name="Voting" />(3 stars)</label> <label><input type="radio" value="4" name="Voting" />(4 stars)</label> <label><input type="radio" value="5" name="Voting" />(5 stars)</label>
    <input type="button" class="default-button" name="AddVote" value="Vote!" onclick="hw.vote(<?=$image->pid?>,$('input[name=Voting]:checked').val())"/>
</div>
</div>


<div class="picture-comments" id="comment-container">
<h3>Picture comments</h3>
<?php $comments = erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_'.$image->pid,'filter' => array('pid' => $image->pid))); 

if (count($comments) > 0) :
?>
<ul>
<?php foreach ($comments as $comment): ?>
<li>
<span class="author"><?=htmlspecialchars($comment->msg_author);?></span>
<div class="right ct"><?=$comment->msg_date;?></div>
<p class="msg-body"><?=htmlspecialchars($comment->msg_body)?></p>
</li>
<?php endforeach;?>
</ul>
<?else:?>
<p>No comments</p>
<?endif;?>


<div class="comment-form">

<? if (isset($commentErrArr)) : ?>
<ul class="error-list">
    <?foreach ($commentErrArr as $error) :?>
        <li><?=$error?></li>
    <?php endforeach;?>
</ul>
<?endif;?>

<? if (isset($commentStored)) : ?>
<ul class="ok">
    <li>Comment stored</li>
</ul>
<?endif;?>

<form action="#comment-container" method="post" >
    <h4>Leave a reply</h4>
    <div class="in-blk">
    <label>Nick:</label>
    <input type="text" name="Name" value="<?=htmlspecialchars($comment_new->msg_author)?>" maxlength="25" class="inputfield"/><i> Max 25 characters</i>
    </div>

    <div class="in-blk">
    <label>Message:</label>
    <textarea name="CommentBody" rows="5" class="default-textarea" ><?=htmlspecialchars($comment_new->msg_body)?></textarea>
    </div>

    <div class="in-blk">
    <label>Safe code</label>
    <input type="text" class="inputfield" name="CaptchaCode" value="" /><br />
    <img src="<?=erLhcoreClassDesign::baseurl('/captcha/image/comment')?>" alt="" />
    </div>    
    <input type="submit" class="default-button" name="StoreComment" value="Comment"/>
</form>
</div>



</div>





</div>
</div>