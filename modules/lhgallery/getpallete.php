<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/getpallete.tpl.php');

$pallete_id = (array)$Params['user_parameters_unordered']['color'];
sort($pallete_id);

$tpl->set('pallete_id',$pallete_id);

$modes = array(
'search',
'color'
);

$mode = in_array($Params['user_parameters_unordered']['mode'],$modes) ? $Params['user_parameters_unordered']['mode'] : 'color';
$keywordDecoded =  trim(str_replace('+',' ',urldecode($Params['user_parameters_unordered']['keyword'])));
$tpl->set('keyword',$keywordDecoded);
$tpl->set('mode',$mode);

echo json_encode(array('result' => $tpl->fetch()));

exit;