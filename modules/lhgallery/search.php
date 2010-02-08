<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/search.tpl.php');


$definition = array(
'SearchText' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
)
);
$form = new ezcInputForm( INPUT_GET, $definition );
    
$searchParams = array('SearchLimit' => 25,'keyword' => '');
$userParams ='';

if ( $form->hasValidData( 'SearchText' ) && $form->SearchText != '')
{
    $searchParams['keyword'] = $form->SearchText;   
    $userParams .= '/(keyword)/'.urlencode($form->SearchText); 
} elseif ($Params['user_parameters_unordered']['keyword'] != '') {
   $userParams .= '/(keyword)/'.$Params['user_parameters_unordered']['keyword'];
   $searchParams['keyword'] = urldecode($Params['user_parameters_unordered']['keyword']);
}

/* SORTING */
$sortModes = array(    
    'newdesc' => '@id DESC',
    'newasc' => '@id ASC',    
    'popular' => 'hits DESC, @id DESC',
    'popularasc' => 'hits ASC, @id ASC',  
    'lasthits'      => 'mtime DESC, @id DESC',
    'lasthitsasc'   => 'mtime ASC, @id ASC',        
    'lastcommented' => 'comtime DESC, @id DESC',
    'lastcommentedasc' => 'comtime ASC, @id ASC',          
    'toprated'         => 'pic_rating DESC, votes DESC, @id DESC',
    'topratedasc'      => 'pic_rating ASC, votes ASC, @id ASC',   
    );
    
$mode = isset($Params['user_parameters_unordered']['sort']) && key_exists($Params['user_parameters_unordered']['sort'],$sortModes) ? $Params['user_parameters_unordered']['sort'] : 'newdesc';
$modeSQL = $sortModes[$mode];         
$appendImageModeSorting = $mode != 'newdesc' ? '/(sort)/'.$mode : '';    
$searchParams['sort'] = $modeSQL;
$userParams .= $appendImageModeSorting;
$appendImageMode = '/(mode)/search/(keyword)/'.urlencode($searchParams['keyword']).$appendImageModeSorting;
/* SORTING */



$firstSearch = false;

$pages = new lhPaginator();
$searchParams['SearchLimit'] = 20;
$searchParams['SearchOffset'] = 0;

if (($totalItems = $Params['user_parameters_unordered']['total']) == null)
{
    $searchResult = erLhcoreClassGallery::searchSphinx($searchParams);
    $totalItems = $searchResult['total_found'];
    $firstSearch = true;
    $userParams .= '/(total)/'.$totalItems;    
    if ($Params['user_parameters_unordered']['page'] !== null ) $firstSearch = false;
    elseif ($totalItems > 0) {        
      erLhcoreClassModelGalleryLastSearch::addSearch($searchParams['keyword'],$totalItems);  
    }
    
} else {
    $userParams .= '/(total)/'.$totalItems; 
}



$pages->items_total = $totalItems;
$pages->translationContext = 'rss/category';
$pages->serverURL = '/gallery/search'.$userParams;
$pages->paginate();

$searchParams['SearchOffset'] = $pages->low;


if ($firstSearch == false){
$searchResult = erLhcoreClassGallery::searchSphinx($searchParams);
}

$sortModesTitle = array(    
    'newdesc' => '',
    'newasc' => 'Last uploaded last',    
    'popular' => 'Most popular first',
    'popularasc' => 'Most popular last',    
    'lasthits' => 'Last hits first',
    'lasthitsasc' => 'Last hits last',    
    'lastcommented' => 'Last commented first',
    'lastcommentedasc' => 'Last commented last',    
    'toprated' => 'Top rated first',
    'topratedasc' => 'Top rated last Last');
    

$tpl->set('pages',$pages);
$tpl->set('items',$searchResult['list']);
$tpl->set('keyword',$searchParams['keyword']);
$tpl->set('appendImageMode',$appendImageMode);
$tpl->set('mode',$mode);

$Result['tittle_prepend'] = $sortModesTitle[$mode];
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => 'search result'));
$Result['title_path'] = array(array('title' => $searchParams['keyword'].' &laquo; search result'));

$Result['keyword'] = $searchParams['keyword'];

?>