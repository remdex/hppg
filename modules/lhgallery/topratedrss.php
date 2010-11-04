<?php

header ("content-type: text/xml");
$cache = CSCacheAPC::getMem(); 
$cacheVersion = $cache->getCacheVersion('top_rated');

if (($xml = $cache->restore(md5($cacheVersion.'_rss_top_rated'))) === false)
{         
    $feed = new ezcFeed(); 
    $feed->title = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/topratedrss','Top rated images');
    $feed->description = '';
    $feed->published = time(); 
    $link = $feed->add( 'link' );
    $link->href = 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('/gallery/toprated/');
    $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'disable_sql_cache' => true,'approved' => 1, 'sort' => 'pic_rating DESC, votes DESC, pid DESC','offset' => 0, 'limit' => 20));    
    foreach ($items as $itemRecord)
    {	
    	
    	    $item = $feed->add( 'item' ); 
    	    $item->title = ($title = $itemRecord->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/topratedrss','View image') : $title;
    	    if ($itemRecord->filetype->player == 'IMAGE'){
	       $item->description = htmlspecialchars('<img src="'.erLhcoreClassDesign::imagePath($itemRecord->filepath.'thumb_'.urlencode($itemRecord->filename)).'" alt="'.htmlspecialchars($itemRecord->name_user).'" />');
	    } else {
	       $item->description = '';
	    }   
	    
	    $item->description .= '<ul>
            <li>'.$itemRecord->pwidth.'x'.$itemRecord->pheight.'</li>
            <li>'.$itemRecord->hits.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lasthitsrss','watched').'</li>                    
            </a></li>
        </ul>';
    	    $item->published = $itemRecord->ctime; 
    	     
    	    $link = $item->add( 'link' );
    	    $link->href = 'http://'.$_SERVER['HTTP_HOST'].$itemRecord->url_path.'/(mode)/toprated'; 
    	
    }
    
    $xml = $feed->generate( 'rss2' ); 
    
    $cache->store(md5($cacheVersion.'_rss_top_rated'),$xml);
}

echo $xml;
exit;

?>