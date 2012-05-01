<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/getnpallete.tpl.php');

$pallete_id = filter_var((array)$Params['user_parameters_unordered']['ncolor'],FILTER_VALIDATE_INT,FILTER_REQUIRE_ARRAY);
sort($pallete_id);

$npallete_id = filter_var((array)$Params['user_parameters_unordered']['color'],FILTER_VALIDATE_INT,FILTER_REQUIRE_ARRAY);
sort($npallete_id);

if (count($npallete_id) > 0){
    $tpl->set('no_color','/(color)/'.implode('/',$npallete_id));
} else {
    $tpl->set('no_color','');
}

$tpl->set('pallete_id',$pallete_id);

$modes = array(
'search',
'color'
);

$resolutions = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'resolutions' );
$resolutionAppend = '';
if (isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions)) {    
    $resolutionAppend = '/(resolution)/'.$resolutions[$Params['user_parameters_unordered']['resolution']]['width'].'x'.$resolutions[$Params['user_parameters_unordered']['resolution']]['height'];
}

$matchAppend = '';
if (isset($Params['user_parameters_unordered']['match']) && $Params['user_parameters_unordered']['match'] == 'all') {    
    $matchAppend = '/(match)/all';
}

$albumAppend = '';
if (isset($Params['user_parameters_unordered']['album']) && is_numeric($Params['user_parameters_unordered']['album'])) {    
    $albumAppend = '/(album)/'.(int)$Params['user_parameters_unordered']['album'];
}

$mode = in_array($Params['user_parameters_unordered']['mode'],$modes) ? $Params['user_parameters_unordered']['mode'] : 'color';
$keywordDecoded =  trim(strip_tags(str_replace('+',' ',urldecode($Params['user_parameters_unordered']['keyword']))));
$tpl->set('keyword',$keywordDecoded);
$tpl->set('mode',$mode);
$tpl->set('resolution',$resolutionAppend);
$tpl->set('match',$matchAppend);
$tpl->set('album',$albumAppend);

echo json_encode(array('result' => $tpl->fetch()));

exit;