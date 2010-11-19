<?php

header ("content-type: text/xml");
$cache = CSCacheAPC::getMem(); 
$cacheVersion = $cache->getCacheVersion('last_rated');

if (($xml = $cache->restore(md5($cacheVersion.'_rss_last_rated'))) === false)
{         
    $feed = new ezcFeed(); 
    $feed->title = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastratedrss','Last rated images');
    $feed->description = '';
    $feed->published = time(); 
    $link = $feed->add( 'link' );
    $link->href = 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('gallery/lastrated');     
    $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'approved' => 1,'disable_sql_cache' => true,'sort' => 'rtime DESC, pid DESC','offset' => 0, 'limit' => 20));    
    foreach ($items as $itemRecord)
    {	
    	   
    	    $item = $feed->add( 'item' ); 
    	    $item->title = ($title = $itemRecord->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastratedrss','View image') : $title;
    	    if ($itemRecord->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE){
    	       $item->description = htmlspecialchars('<img src="'.erLhcoreClassDesign::imagePath($itemRecord->filepath.'thumb_'.urlencode($itemRecord->filename)).'" alt="'.htmlspecialchars($itemRecord->name_user).'" />');
    	    } else {
    	       $item->description = '';
    	    }   
	    
	    $item->description .= '<ul>
            <li>'.$itemRecord->pwidth.'x'.$itemRecord->pheight.'</li>
            <li>'.$itemRecord->hits.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthitsrss','watched').'</li>                    
            </a></li>
        </ul>';
    	    $item->published = $itemRecord->rtime; 
    	     
    	    $link = $item->add( 'link' );
    	    $link->href = 'http://'.$_SERVER['HTTP_HOST'].$itemRecord->url_path.'/(mode)/lastrated'; 
    	
    }
    
    $xml = $feed->generate( 'rss2' ); 
    
    $cache->store(md5($cacheVersion.'_rss_last_rated'),$xml);
}

echo $xml;
exit;

?>