<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/getpallete.tpl.php');

$pallete_id = (array)$Params['user_parameters_unordered']['color'];
sort($pallete_id);

$npallete_id = (array)$Params['user_parameters_unordered']['ncolor'];
sort($npallete_id);

if (count($npallete_id) > 0){
    $tpl->set('no_color','/(ncolor)/'.implode('/',$npallete_id));
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

$mode = in_array($Params['user_parameters_unordered']['mode'],$modes) ? $Params['user_parameters_unordered']['mode'] : 'color';
$keywordDecoded =  trim(str_replace('+',' ',urldecode($Params['user_parameters_unordered']['keyword'])));
$tpl->set('keyword',$keywordDecoded);
$tpl->set('mode',$mode);
$tpl->set('resolution',$resolutionAppend);
$tpl->set('match',$matchAppend);


echo json_encode(array('result' => $tpl->fetch()));

exit;