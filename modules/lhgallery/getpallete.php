<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/getpallete.tpl.php');

$pallete_id = (array)$Params['user_parameters_unordered']['color'];
$tpl->set('pallete_id',$pallete_id);

echo json_encode(array('result' => $tpl->fetch()));

exit;