<? 
switch ($mode) {
    
	case 'album': 
    	// All in controller
    break;
	
	case 'lastuploads': 	 
    	$urlAppend = '/(mode)/lastuploads';
    	if ($direction == 'left'){
    	   $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 5,'sort' => 'ctime ASC','filtergt' => array('pid' => $image->pid)));
    	   rsort($imagesAjax);   
    	} else {
    	   $imagesAjax = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'version_'.CSCacheAPC::getMem()->getCacheVersion('last_uploads'),'limit' => 5,'sort' => 'ctime DESC','filterlt' => array('pid' => $image->pid))); 
    	}  
        break;
        	
	case 'search':
	   // All in controller 		 	       
	break;
	
	case 'popular': 
	    $urlAppend = '/(mode)/popular';    	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );    	
    	if ($direction == 'left'){
    	    $q->where( $q->expr->gt( 'hits', $q->bindValue( $image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $image->hits ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
            ->orderBy('hits ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	    $q->where( $q->expr->lt( 'hits', $q->bindValue( $image->hits ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( $image->hits ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
            ->orderBy('hits DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	};break;
	
	 case 'lasthits': 
    	$urlAppend = '/(mode)/lasthits';    	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );    	
    	if ($direction == 'left'){
    	   $q->where( $q->expr->gt( 'mtime', $q->bindValue( $image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $image->mtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
            ->orderBy('mtime ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	   $q->where( $q->expr->lt( 'mtime', $q->bindValue( $image->mtime ) ). ' OR '.$q->expr->eq( 'mtime', $q->bindValue( $image->mtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
            ->orderBy('mtime DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	}          
	   break;  	       
	    
	case 'lastcommented':	    
    	$urlAppend = '/(mode)/lastcommented';    	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );    	
    	if ($direction == 'left'){
    	   $q->where( $q->expr->gt( 'comtime', $q->bindValue( $image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $image->comtime ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ) )
            ->orderBy('comtime ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	   $q->where( $q->expr->lt( 'comtime', $q->bindValue( $image->comtime ) ). ' OR '.$q->expr->eq( 'comtime', $q->bindValue( $image->comtime ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ) )
            ->orderBy('comtime DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	}	   
	   break;
	
	case 'toprated': 
    	$urlAppend = '/(mode)/toprated';    	
    	$session = erLhcoreClassGallery::getSession();
    	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );    	
    	if ($direction == 'left'){
    	    $q->where( $q->expr->gt( 'pic_rating', $q->bindValue( $image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->gt( 'votes', $q->bindValue( $image->votes ) ).' OR '.
            $q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $image->votes ) ).' AND '.$q->expr->gt( 'pid', $q->bindValue( $image->pid ) ))
            ->orderBy('pic_rating ASC, votes ASC, pid ASC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
            $imagesAjax = array_reverse($imagesAjax);
    	} else {
    	    $q->where( $q->expr->lt( 'pic_rating', $q->bindValue( $image->pic_rating ) ). ' OR '.$q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->lt( 'votes', $q->bindValue( $image->votes ) ).' OR '.
            $q->expr->eq( 'pic_rating', $q->bindValue( $image->pic_rating ) ).' AND '.$q->expr->eq( 'votes', $q->bindValue( $image->votes ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( $image->pid ) ))
            ->orderBy('pic_rating DESC, votes DESC, pid DESC')
            ->limit( 5 );
            $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
    	};break;
	
	default:
		break;
}
if (count($imagesAjax) > 0) :
$lastImages = current($imagesAjax);
?>
<div class="left-ajax">
<a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$lastImages->pid?><?=$urlAppend?>"></a>
</div>
<? foreach ($imagesAjax as $key => $item) : ?>
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
end($imagesAjax);
$lastImages = current($imagesAjax);
?> 
<div class="right-ajax">
<a href="#" rel="<?=erLhcoreClassDesign::baseurl('/gallery/ajaximages/')?><?=$lastImages->pid?><?=$urlAppend?>"></a>
</div>
<script type="text/javascript">
$('.right-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'right');
   return false;
});
$('.left-ajax a').click(function(){
    hw.getimages($(this).attr('rel'),'left');
   return false;
});
</script>
<?endif;?>