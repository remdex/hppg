<?php

$definition = array(
'SearchText' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
)
);
$form = new ezcInputForm( INPUT_GET, $definition );
    
$searchParams = array('SearchLimit' => 25,'keyword' => '');
$userParams ='';

if ( $form->hasValidData( 'SearchText' ) && trim($form->SearchText) != '')
{
    $searchParams['keyword'] = trim(str_replace('+',' ',$form->SearchText));
    $userParams .= '/(keyword)/'.urlencode(trim($form->SearchText));
} elseif ($Params['user_parameters_unordered']['keyword'] != '') {

   // We have to reencode because ngnix or php-fpm somewhere wrongly parses it. 
   $keywordDecoded =  trim(str_replace('+',' ',urldecode($Params['user_parameters_unordered']['keyword'])));
   $userParams .= '/(keyword)/'.urlencode($keywordDecoded);
   $searchParams['keyword'] = $keywordDecoded;
}

/* SORTING */
$sortModes = array(    
    'new'               => '@id DESC',
    'newasc'            => '@id ASC',    
    'popular'           => 'hits DESC, @id DESC',
    'popularasc'        => 'hits ASC, @id ASC',  
    'lasthits'          => 'mtime DESC, @id DESC',
    'lasthitsasc'       => 'mtime ASC, @id ASC',        
    'lastcommented'     => 'comtime DESC, @id DESC',
    'lastcommentedasc'  => 'comtime ASC, @id ASC',         
    'lastrated'         => 'rtime DESC, @id DESC',
    'lastratedasc'      => 'rtime ASC, @id ASC',          
    'toprated'          => 'pic_rating DESC, votes DESC, @id DESC',
    'topratedasc'       => 'pic_rating ASC, votes ASC, @id ASC',
    'relevance'         => '@relevance DESC, @id DESC',
    'relevanceasc'      => '@relevance ASC, @id ASC',
);

$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' );
    
$mode = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'relevance';
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';
$matchMode = $Params['user_parameters_unordered']['match'] == 'all' ? 'all' : '';

$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
if ($resolution != ''){
    $searchParams['Filter']['pwidth'] = $resolutions[$resolution]['width'];
    $searchParams['Filter']['pheight'] = $resolutions[$resolution]['height'];
}

$appendMatchMode = '';
if ($matchMode != ''){
    $searchParams['MatchMode'] = 'all';
    $appendMatchMode = '/(match)/all';
}

// Search also includes desirable color
$appendColorMode = '';
$pallete_id = (array)$Params['user_parameters_unordered']['color'];
$pallete_items_number = count($pallete_id);
if ($pallete_items_number > 0) {  
    if ($pallete_items_number > erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters')) {
        $pallete_id = array_slice($pallete_id,0,erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));
        $pallete_items_number = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters');
    }
    sort($pallete_id);
    $appendColorMode = '/(color)/'.implode('/',$pallete_id);
    $searchParams['color_filter'] = $pallete_id;
}


$modeSQL = $sortModes[$mode];         
$appendImageModeSorting = $mode != 'relevance' ? '/(sort)/'.$mode : '';    
$searchParams['sort'] = $modeSQL;
$userParams .= $appendImageModeSorting;
$userParamsWithoutResolution = $userParams.$appendColorMode;
$userParams .= $appendColorMode.$appendResolutionMode.$appendMatchMode;

$appendImageMode = '/(mode)/search/(keyword)/'.urlencode($searchParams['keyword']).$appendImageModeSorting.$appendColorMode.$appendResolutionMode.$appendMatchMode;
/* SORTING */

$searchParams['SearchLimit'] = 20;
$searchParams['SearchOffset'] = 0;

$cache = CSCacheAPC::getMem();        
$cacheKey =  md5('SphinxSearchPage_VersionCache'.$cache->getCacheVersion('sphinx_cache_version').erLhcoreClassGallery::multi_implode(',',$searchParams).'page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
      
if (($Result = $cache->restore($cacheKey)) === false)
{
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/search.tpl.php');
    
    if ($searchParams['keyword'] != '')
    {         
        $tpl->set('max_filters',$pallete_items_number == erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'maximum_filters'));    
        $tpl->set('pallete_id',$pallete_id);
        
        if ($pallete_items_number > 0) {
            $tpl->set('palletes',erLhcoreClassModelGalleryPallete::getList(array('filterin' => array('id' => $pallete_id))));
        }
             
          
        $tpl->set('enter_keyword',false);
        $pages = new lhPaginator();
                  
        $searchParams['SearchOffset'] = $pages->low;
        $searchResult = erLhcoreClassGallery::searchSphinx($searchParams,false);
        
        if ($pages->low == 0 && $searchResult['total_found'] > 0) {
            erLhcoreClassModelGalleryLastSearch::addSearch($searchParams['keyword'],$searchResult['total_found']); 
        }
        
        $pages->items_total = $searchResult['total_found'];
        $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/search').$userParams;
        $pages->paginate();        
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/search').$userParams.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : '');        
        $sortModesTitle = array(    
            'new' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded first'),
            'newasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded last'),    
            'popular' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular first'),
            'popularasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular last'),    
            'lasthits' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits first'),
            'lasthitsasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits last'),    
            'lastcommented' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented first'),
            'lastcommentedasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented last'),    
            'lastrated' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last rated first'),
            'lastratedasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last rated last'),    
            'toprated' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated first'),
            'topratedasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated last'),
            'relevance' => '',
            'relevanceasc' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most relevance images last')
         );
                
        $tpl->setArray ( array (
                'pages'             => $pages,
                'items'             => $searchResult['list'],
                'keyword'           => $searchParams['keyword'],
                'appendImageMode'   => $appendImageMode,
                'mode'              => $mode,
                'matchMode'         => $matchMode,
                'currentResolution' => $resolution
        ) );
        
        
        $Result['tittle_prepend'] = $sortModesTitle[$mode];
        $Result['content'] = $tpl->fetch();
        $Result['path'] = array(array('url' =>erLhcoreClassDesign::baseurl('gallery/search').$userParamsWithoutResolution, 'title' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/searchrss','Search results').' - '.$searchParams['keyword']))   ;
        $Result['title_path'] = array(array('title' => $searchParams['keyword'].' &laquo; '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','search results')));
        
        if ($resolution != '') {
            $Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('gallery/search').$userParams,'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Resolution').' - '.$resolution);  
            $Result['title_path'][] = array('title' => $resolution); 
        }
        
        if ($Params['user_parameters_unordered']['page'] > 1) {        
            $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
            $Result['title_path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
        }
             
        $Result['keyword'] = $searchParams['keyword'];
        $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Search RSS by keyword').' - '.htmlspecialchars($searchParams['keyword']);
        $Result['rss']['url'] = erLhcoreClassDesign::baseurl('gallery/searchrss').'/(keyword)/'.urlencode($searchParams['keyword']); 
        $cache->store($cacheKey,$Result,12000);
    } else {
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/search');
        $tpl->set('enter_keyword',true);
        $Result['path'] = array(array('url' =>erLhcoreClassDesign::baseurl('gallery/search'), 'title' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/searchrss','Search')));
        $Result['content'] = $tpl->fetch();
        $cache->store($cacheKey,$Result,12000);
    }
}


?>