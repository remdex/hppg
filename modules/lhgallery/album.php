<?php
$sortModes = array(    
    'new' => 'pid DESC',
    'newasc' => 'pid ASC',    
    'popular' => 'hits DESC, pid DESC',
    'popularasc' => 'hits ASC, pid ASC',    
    'lasthits' => 'mtime DESC, pid DESC',
    'lasthitsasc' => 'mtime ASC, pid ASC',    
    'lastcommented' => 'comtime DESC, pid DESC',
    'lastcommentedasc' => 'comtime ASC, pid ASC',    
    'toprated' => 'pic_rating DESC, votes DESC, pid DESC',
    'topratedasc' => 'pic_rating ASC, votes ASC, pid ASC',    
    'lastrated' => 'rtime DESC, pid DESC',
    'lastratedasc' => 'rtime ASC, pid ASC'
    );
    
$appendCacheModes = array(

'popular' => array('key' => 'most_popular_version','defvalue' => time(),'ttl' => 1500),
'popularasc' => array('key' => 'most_popular_version','defvalue' => time(),'ttl' => 1500),
'lasthits' => array('key' => 'last_hits_version','defvalue' => time(),'ttl' => 600),
'lasthitsasc' => array('key' => 'last_hits_version','defvalue' => time(),'ttl' => 600),
'lastcommented' => array('key' => 'last_commented_'.(int)$Params['user_parameters']['album_id'],'defvalue' => 1,'ttl' => 0),
'lastcommentedasc' => array('key' => 'last_commented_'.(int)$Params['user_parameters']['album_id'],'defvalue' =>1,'ttl' => 0),
'toprated' => array('key' => 'top_rated_'.(int)$Params['user_parameters']['album_id'],'defvalue' =>1,'ttl' => 0),
'topratedasc' => array('key' => 'top_rated_'.(int)$Params['user_parameters']['album_id'],'defvalue' =>1,'ttl' => 0),
'lastrated' => array('key' => 'last_rated_'.(int)$Params['user_parameters']['album_id'],'defvalue' =>1,'ttl' => 0),
'lastratedasc' => array('key' => 'last_rated_'.(int)$Params['user_parameters']['album_id'],'defvalue' =>1,'ttl' => 0),
);
    
$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );
       
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';
$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
$filterArray = array();    
if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
}
$filterArray['approved'] = 1;

$mode = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'new';

$cache = CSCacheAPC::getMem(); 
     
$appendCacheKey = '';

// We need extra cache key in these cases
if (key_exists($mode,$appendCacheModes))
{    
    $appendCacheKey = 'append_cache_'.$mode.'_version_'.$cache->getCacheVersion($appendCacheModes[$mode]['key'],$appendCacheModes[$mode]['defvalue'],$appendCacheModes[$mode]['ttl']).'_key_'.$appendCacheModes[$mode]['key'];
}

$cacheKey = md5('version_'.$cache->getCacheVersion('album_'.(int)$Params['user_parameters']['album_id']).$mode.$resolution.'album_view_url'.(int)$Params['user_parameters']['album_id'].'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.$appendCacheKey);
  
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'etag_caching_enabled' ) === true)
{
    $currentKeyEtag = md5($cacheKey.'user_id_'.erLhcoreClassUser::instance()->getUserID());;
    header('Cache-Control: must-revalidate'); // must-revalidate
	header('ETag: ' . $currentKeyEtag);
    
    $iftag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] == $currentKeyEtag : null;         
    if ($iftag === true)
    {   
        header ("HTTP/1.0 304 Not Modified");
        header ('Content-Length: 0');
        exit;
    }
} 

if (($Result = $cache->restore($cacheKey)) === false)
{  
    
        
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/album.tpl.php');
    try {
    $Album = erLhcoreClassModelGalleryAlbum::fetch((int)$Params['user_parameters']['album_id']); 
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
    }    
    
    $modeSQL = $sortModes[$mode];         
    $appendImageMode = $mode != 'new' ? '/(sort)/'.$mode : '';
    $appendImageMode .= $appendResolutionMode;
    
    $sortModesTitle = array(    
    'new'               => '',
    'newasc'            => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last uploaded last'),    
    'popular'           => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Most popular first'),
    'popularasc'        => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Most popular last'),    
    'lasthits'          => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last hits first'),
    'lasthitsasc'       => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last hits last'),    
    'lastcommented'     => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last commented first'),
    'lastcommentedasc'  => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last commented last'),    
    'lastrated'         => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last rated first'),
    'lastratedasc'      => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last rated last'),    
    'toprated'          => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Top rated first'),
    'topratedasc'       => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Top rated last Last')
    );
      
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => 'albumlist_'.$cache->getCacheVersion('album_'.$Album->aid),'filter' => array('aid' => $Album->aid)+$filterArray));
    $pages->serverURL = $Album->url_path.$appendImageMode;
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $tpl->set('album',$Album);
    $tpl->set('currentResolution',$resolution);
    $tpl->set('filterArray',$filterArray);
    $tpl->set('appendImageMode',$appendImageMode);
       
    $tpl->set('modeSQL',$modeSQL);
    $tpl->set('mode',$mode);
    
    $Result['content'] = $tpl->fetch();
    $Result['path'] = $Album->path;
    
          
    if ($resolution != '') {
        $Result['path'][] = array('url' => $Album->url_path.$appendImageMode,'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Resolution').' - '.$resolution);  
    }
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $Result['tittle_prepend'] = $sortModesTitle[$mode];
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last uploaded images to album').' - '.$Album->title;
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/albumrss/').$Album->aid;  
    
    $cache->store($cacheKey,$Result);
}

?>