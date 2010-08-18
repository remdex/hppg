<div class="image-full">
<div class="image-full-content">

<div class="header-list">
<h1><?=htmlspecialchars($image->name_user)?></h1>
</div>

<div class="navigator float-break">

<? switch ($mode) {
    
	case 'album': ?>		
    	<? 
    	if (count($imagesLeft) > 0) :   
    	$next_image = current($imagesLeft); 
    	$imagesLeft = array_reverse($imagesLeft);    	
    	?>
        <a class="left-image" href="<?=$next_image->url_path.$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>
         <? endif; ?>
         
        <? 
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight); 
        ?>
        <a class="right-image" href="<?=$prev_image->url_path.$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a> 
        <?
        endif;
        $pageAppend = $page > 1 ? '/(page)/'.$page : '';
        ?>        
        <a class="ret-thumb" href="<? echo erLhcoreClassModelGalleryAlbum::fetch($image->aid)->url_path,$pageAppend,$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?></a>        
                
        <?php $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
        
        break;
	
	case 'myfavorites': ?>		
    	<? 
    	if (count($imagesLeft) > 0) :   
    	$next_image = current($imagesLeft); 
    	$imagesLeft = array_reverse($imagesLeft);    	
    	?>
        <a class="left-image" href="<?=$next_image->url_path.$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>
        <? endif; ?>
                
        <? 
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight); 
        ?>
        <a class="right-image" href="<?=$prev_image->url_path.$urlAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a> 
        <?
        endif;
        $pageAppend = $page > 1 ? '/(page)/'.$page : '';
        ?>        
        <a class="ret-thumb" href="<? echo erLhcoreClassDesign::baseurl('/gallery/myfavorites'),$pageAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to favorites')?></a>        
        
        <?php $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
        
        break;
	
	case 'lastuploads': ?>		
    	<? 
    	$urlAppend = '/(mode)/lastuploads';
    	$imagesLeft = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 2,'sort' => 'ctime ASC','filtergt' => array('pid' => $image->pid)));    	
    	if (count($imagesLeft) > 0) :    	
    	$next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft);        	
    	?>
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/lastuploads"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>
        <? endif; ?>
         
        <? 
        $imagesRight = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 2,'sort' => 'ctime DESC','filterlt' => array('pid' => $image->pid)));
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);
        ?>      
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/lastuploads"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a>
              
        <? 
        endif;
        $page = ceil(erLhcoreClassModelGalleryImage::getImageCount(array('filtergt' => array('pid' => $image->pid)))/20);
        $pageAppend = $page > 1 ? '/(page)/'.$page : '';
        ?>        
        <a class="ret-thumb" href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploads')?><?=$pageAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?></a>        
            
        <?php $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
        
        break;
	
	case 'search':	
	if (count($imagesLeft) > 0) :
    $next_image = current($imagesLeft);
    $imagesLeft = array_reverse($imagesLeft);             
	?>
    <a class="left-image" href="<?=$next_image->url_path?>/(mode)/search/(keyword)/<?php echo urlencode($keyword),$mode_sort_append?>" ><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>
    <?endif; ?>
    
    <? 
	if (count($imagesRight) > 0) :
	$next_image = current($imagesRight);
	?>	
    <a class="right-image" href="<?=$next_image->url_path?>/(mode)/search/(keyword)/<?php echo urlencode($keyword),$mode_sort_append?>" ><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a>     
		
	<? 
    endif;
	$pageAppend = $page > 1 ? '/(page)/'.$page : '';
	?>
    <a class="ret-thumb" href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/<?=urlencode($keyword)?><?php echo $pageAppend,$mode_sort_append?>">return to thumbnails</a>      
        
	<?php $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	break;
	
	case 'popular': ?>		    		
    	<?     	    	
    	$urlAppend = '/(mode)/popular';
    	$db = ezcDbInstance::get(); 
        $session = erLhcoreClassGallery::getSession();
        
        $cache = CSCacheAPC::getMem();   
		$cacheVersion = $cache->getCacheVersion('most_popular_version',time(),1500);
        
        
		$cacheKey = md5('popular_left_thumbnails_'.$cacheVersion.'_hits_'.$image->hits.'_pid_'.$image->pid);
		if (($imagesLeft = $cache->restore($cacheKey)) === false)
		{
	        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
	        $q->where( $q->expr->gt( 'hits', $q->bindValue( $image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
	        ->orderBy('hits ASC, pid ASC')
	        ->limit( 2 );
	        $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
	        $cache->store($cacheKey,$imagesLeft);
		}
               
        if (count($imagesLeft) > 0) :   
    	$next_image = current($imagesLeft);
    	$imagesLeft = array_reverse($imagesLeft); 
    	?>
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/popular">previous image</a>             
        <?  endif; ?>
        
        <?  
        $cacheKey = md5('popular_right_thumbnails_'.$cacheVersion.'_hits_'.$image->hits.'_pid_'.$image->pid);        
        if (($imagesRight = $cache->restore($cacheKey)) === false)
		{
	        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
	        $q->where( $q->expr->lt( 'hits', $q->bindValue( $image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
	        ->orderBy('hits DESC, pid DESC')
	        ->limit( 2 );
	        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );	        
	        $cache->store($cacheKey,$imagesRight); 
		}        
               
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);         
    	?>       
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/popular">next image</a>       
	   <?endif;?>
	   	   
  		<?php $cacheKey = md5('popular_count_thumbnails_'.$cacheVersion.'_hits_'.$image->hits.'_pid_'.$image->pid);
		if (($photos = $cache->restore($cacheKey)) === false)
		{
	        $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE hits > :hits OR hits = :hits AND pid > :pid LIMIT 1');
	        $stmt->bindValue( ':hits',$image->hits);
	        $stmt->bindValue( ':pid',$image->pid);       
	        $stmt->execute();
	        $photos = $stmt->fetchColumn(); 
	        $cache->store($cacheKey,$photos);
		}
        		
        $page = ceil(($photos+1)/20);
	    $pageAppend = $page > 1 ? '/(page)/'.$page : '';
    	?>    	
        <a class="ret-thumb" href="/gallery/popular<?=$pageAppend?>">return to thumbnails</a>        
         
	   <?php $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
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
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/lasthits"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>             
        <?endif;?> 	
        
        <? 
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->lt( 'mtime', $q->bindValue( $image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('mtime DESC, pid DESC')
        ->limit( 2 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);              
    	?>       
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/lasthits"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a> 
	   <?endif;?>
	   	   
        <?php $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE mtime > :mtime OR mtime = :mtime AND pid > :pid LIMIT 1');
            $stmt->bindValue( ':mtime',$image->mtime);
            $stmt->bindValue( ':pid',$image->pid);       
            $stmt->execute();  
            $photos = $stmt->fetchColumn();         
            $page = ceil(($photos+1)/20);
    	    $pageAppend = $page > 1 ? '/(page)/'.$page : '';  
    	?>    	
        <a class="ret-thumb" href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?><?=$pageAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?></a>        
           
	   <?php $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
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
        <a class="left-image" href="<?=$next_image->url_path?>/(mode)/lastcommented"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>             
        <?endif; ?>    	
        
        <?   
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
        $q->where( $q->expr->lt( 'comtime', $q->bindValue( $image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
        ->orderBy('comtime DESC, pid DESC')
        ->limit( 2 );
        $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        if (count($imagesRight) > 0) :
        $prev_image = current($imagesRight);
    	?>       
        <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/lastcommented"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a> 
	   <?endif;?>
	           
        <?php $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE comtime > :comtime OR comtime = :comtime AND pid > :pid LIMIT 1');
        $stmt->bindValue( ':comtime',$image->comtime);
        $stmt->bindValue( ':pid',$image->pid);       
        $stmt->execute();
        $photos = $stmt->fetchColumn();
        $page = ceil(($photos+1)/20);
	    $pageAppend = $page > 1 ? '/(page)/'.$page : ''; 
    	?>    	
        <a  class="ret-thumb" href="<?=erLhcoreClassDesign::baseurl('gallery/lastcommented')?><?=$pageAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?></a>        
         
	   <?php
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
       <a class="left-image" href="<?=$next_image->url_path?>/(mode)/toprated"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','previous image')?></a>                            
       <?php endif;  ?>  
       
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
       <a class="right-image" href="<?=$prev_image->url_path?>/(mode)/toprated"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','next image')?></a>     
	   <?endif;?>
       
       <?php $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_images WHERE pic_rating > :pic_rating OR pic_rating = :pic_rating AND lh_gallery_images.votes > :votes OR pic_rating = :pic_rating AND lh_gallery_images.votes = :votes AND pid > :pid');
       $stmt->bindValue( ':pic_rating',$image->pic_rating);       
       $stmt->bindValue( ':votes',$image->votes);       
       $stmt->bindValue( ':pid',$image->pid);       
       $stmt->execute();
       $photos = $stmt->fetchColumn();         
       $page = ceil(($photos+1)/20);
	   $pageAppend = $page > 1 ? '/(page)/'.$page : '';                     
       ?>            
       <a class="ret-thumb" href="<?=erLhcoreClassDesign::baseurl('gallery/toprated')?><?=$pageAppend?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','return to thumbnails')?></a>                
       	
	<?php
	   $imagesAjax = array_merge((array)$imagesLeft,array($image->pid => $image),(array)$imagesRight);
	break;
	
	default:
		break;
}
?>



</div>


<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_window.tpl.php'));?>

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_control_block.tpl.php'));?>  


<div id="ajax-navigator-content" class="float-break">

<?php if (count($imagesRight) > 0) :
end($imagesAjax);
$lastImages = current($imagesAjax);
?> 
<div class="right-ajax">
<a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$lastImages->pid?><?=$urlAppend?>"></a>
</div>
<?php endif;?>

<?php
if (count($imagesLeft) > 0) :
reset($imagesAjax);
$lastImages = current($imagesAjax);
?>
<div class="left-ajax">
<a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$lastImages->pid?><?=$urlAppend?>"></a>
</div>

<div class="navigator-ajax float-break" >
<? 
endif;
$counter = 1;
foreach ($imagesAjax as $key => $item) : ?>
    <div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
        <div class="thumb-pic">
            <a href="<?=$item->url_path?><?=isset($urlAppend) ? $urlAppend : ''?>"><img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size')?>" src="<?=erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename))?>" alt="<?=htmlspecialchars($item->name_user);?>" /></a>           
        </div>
        <div class="thumb-attr">
        
        <div class="tit-item">
            <h3><a title="<?=htmlspecialchars($item->name_user);?>" href="<?=$item->url_path?><?=isset($urlAppend) ? $urlAppend : ''?>">
                <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
                </a>
            </h3>
        </div>
        
        <span class="res-ico">
        <?=$item->pwidth?>x<?=$item->pheight?>
        </span>    
        
        <span class="hits-ico">
        <?=$item->hits?>
        </span>               
        
        </div>
    </div>     
<?$counter++;endforeach;?> 
</div>


</div>


<script type="text/javascript">
$('#photo_full').each(function(index) {	
	$(this).colorbox({href:$(this).attr('href')});	
	$(this).attr('href','');
});
$('.right-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'right');
   return false;
});
$('.left-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'left');
   return false;
});
$('.ad-fv').click(function(){
    hw.addToFavorites(<?=$image->pid?>);
   return false;
});

$('.ad-html').colorbox();
$('.ad-phpbb').colorbox();

<?php if ($image->anaglyph == 1) : ?>
$('.ad-anaglyph').colorbox({width:'<?=$image->pwidth+50?>px',height:'<?=$image->pheight+130?>px'});
<?php endif;?>

$("div.image-thumb").mouseover(function() {
    $(this).addClass('image-thumb-shadow');
  }).mouseout(function(){
    $(this).removeClass('image-thumb-shadow');
  });
    
</script>



<div class="picture-details">
<div class="sub-header">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture details')?></h3>
</div>
<ul>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Filename')?>: <?=htmlspecialchars($image->filename);?></li>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','File size')?>: <?=$image->filesize_user;?></li>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Image rating')?><?=$image->votes > 0 ? ' ('.$image->votes.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','votes').')' : ''?>: <img src="<?php echo erLhcoreClassDesign::design('images/gallery/rating'.round($image->pic_rating/2000).'.gif');?>" alt=""/></li>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Date added')?>: <?=date('Y-m-d H:i:s',$image->ctime);?></li>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Dimensions')?>: <?=$image->pwidth?>x<?=$image->pheight?></li>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Displayed')?>: <?=$image->hits?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','times')?></li>
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','URL')?>: <a href="http://<?=$_SERVER['HTTP_HOST']?><?=$image->url_path?>">http://<?=$_SERVER['HTTP_HOST']?><?=$image->url_path?></a></li>
</ul>
</div>

<div class="picture-voting">
<div class="sub-header">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Rate this picture')?></h3>
</div>
<div id="vote-content">
    <label><input type="radio" checked="checked" value="1" name="Voting" />(1 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','star')?>)</label> <label><input type="radio" value="2" name="Voting" /> (2 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label> <label><input type="radio" value="3" name="Voting" />(3 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label> <label><input type="radio" value="4" name="Voting" />(4 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label> <label><input type="radio" value="5" name="Voting" />(5 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label>
    <input type="button" class="default-button" name="AddVote" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Vote!')?>" onclick="hw.vote(<?=$image->pid?>,$('input[name=Voting]:checked').val())"/>
</div>
</div>


<div class="picture-comments" id="comment-container">
<div class="sub-header">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture comments')?></h3>
</div>
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
<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','No comments')?></p>
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
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Comment stored')?></li>
</ul>
<?endif;?>

<form action="#comment-container" method="post" >
    <h4><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Leave a reply')?></h4>
    <div class="in-blk">
    <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Nick')?>:</label>
    <input type="text" name="Name" value="<?=htmlspecialchars($comment_new->msg_author)?>" maxlength="25" class="inputfield"/><i> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Max 25 characters')?></i>
    </div>

    <div class="in-blk">
    <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Message')?>:</label>
    <textarea name="CommentBody" rows="5" cols="10" class="default-textarea" ><?=htmlspecialchars($comment_new->msg_body)?></textarea>
    </div>

    <div class="in-blk">
    <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Safe code')?></label>
    <input type="text" class="inputfield" name="CaptchaCode" value="" /><br />
    <img src="<?=erLhcoreClassDesign::baseurl('/captcha/image/comment/')?><?php echo time();?>" alt="" />
    </div>    
    <input type="submit" class="default-button" name="StoreComment" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Send')?>"/>
</form>
</div>



</div>





</div>
</div>