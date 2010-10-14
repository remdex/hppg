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
    'topratedasc' => 'pic_rating ASC, votes ASC, pid ASC');
    
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
$cacheKey = md5('version_'.$cache->getCacheVersion('album_'.(int)$Params['user_parameters']['album_id']).$mode.$resolution.'album_view_url'.(int)$Params['user_parameters']['album_id'].'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
    
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
    'toprated'          => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Top rated first'),
    'topratedasc'       => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Top rated last Last')
    );
      
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => 'albumlist_'.$cache->getCacheVersion('album_'.$Album->aid),'filter' => array('aid' => $Album->aid)+$filterArray));
    $pages->translationContext = 'gallery/album';
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
        $Result['path'][] = array('url' => $Album->url_path.$appendImageMode,'title' => 'Resolution - '.$resolution);  
    }
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => 'Page - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $Result['tittle_prepend'] = $sortModesTitle[$mode];
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Last uploaded images to album').' - '.$Album->title;
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/albumrss/').$Album->aid;  
    
    $cache->store($cacheKey,$Result);
}

?>