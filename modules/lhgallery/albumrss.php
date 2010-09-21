<?php

header ("content-type: text/xml");

$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('album_'.(int)$Params['user_parameters']['album_id']).'album_rss'.(int)$Params['user_parameters']['album_id']);
 
try {
    $Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', (int)$Params['user_parameters']['album_id'] ); 
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
    }

if (($xml = $cache->restore($cacheKey)) === false)
{         
    $feed = new ezcFeed(); 
    $feed->title = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumrss','Last uploaded images to album').' - '.$Album->title;
    $feed->description = '';
    $feed->published = time(); 
    $link = $feed->add( 'link' );
    $link->href = 'http://'.$_SERVER['HTTP_HOST'].$Album->url_path; 
    $items = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'filter' => array('approved' => 1,'aid' => $Album->aid),'offset' => 0, 'limit' => 20));
       
    foreach ($items as $itemRecord)
    {	
    	
    	    $item = $feed->add( 'item' ); 
    	    $item->title = ($title = $itemRecord->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumrss','View image') : $title;
    	    $item->description = htmlspecialchars('<img src="'.erLhcoreClassDesign::imagePath($itemRecord->filepath.'thumb_'.urlencode($itemRecord->filename)).'" alt="'.htmlspecialchars($itemRecord->name_user).'" />').	    
    	   '<ul>
                <li>'.$itemRecord->pwidth.'x'.$itemRecord->pheight.'</li>
                <li>'.$itemRecord->hits.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumrss','watched').'</li>                    
                </a></li>
            </ul>';
    	    $item->published = $itemRecord->ctime; 
    	     
    	    $link = $item->add( 'link' );
    	    $link->href = 'http://'.$_SERVER['HTTP_HOST'].$itemRecord->url_path; 
    	
    }
    
    $xml = $feed->generate( 'rss2' ); 
    
    $cache->store($cacheKey,$xml);
}

echo $xml;
exit;

?>