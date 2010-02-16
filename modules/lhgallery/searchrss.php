<?php

header ("content-type: text/xml");       
$feed = new ezcFeed(); 
$feed->title = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/searchrss','Search rss by keyword').' - '.urldecode($Params['user_parameters_unordered']['keyword']);
$feed->description = '';
$feed->published = time(); 
$link = $feed->add( 'link' );
$link->href = 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('/gallery/search').'/(keyword)/'.$Params['user_parameters_unordered']['keyword'];
$searchParams = array('SearchLimit' => 20,'keyword' => urldecode($Params['user_parameters_unordered']['keyword']));
$searchResult = erLhcoreClassGallery::searchSphinx($searchParams);
   
foreach ($searchResult['list'] as $itemRecord)
{		
	    $item = $feed->add( 'item' ); 
	    $item->title = ($title = $itemRecord->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/searchrss','View image') : $title;
	    $item->description = htmlspecialchars('<img src="'.erLhcoreClassDesign::imagePath($itemRecord->filepath.'thumb_'.urlencode($itemRecord->filename)).'" alt="'.htmlspecialchars($itemRecord->name_user).'" />').	    
	   '<ul>
            <li>'.$itemRecord->pwidth.'x'.$itemRecord->pheight.'</li>
            <li>'.$itemRecord->hits.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/searchrss','watched').'</li>                    
            </a></li>
        </ul>';;
	    $item->published = $itemRecord->ctime; 
	     
	    $link = $item->add( 'link' );
	    $link->href = 'http://'.$_SERVER['HTTP_HOST'].$itemRecord->url_path.'/(mode)/search/(keyword)/'.$Params['user_parameters_unordered']['keyword']; 	
}

$xml = $feed->generate( 'rss2' );  

echo $xml;
exit;

?>