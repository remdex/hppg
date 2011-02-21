<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/translatebox.tpl.php');
$tpl->set('msg_id',(int)$Params['user_parameters']['msg_id']);
echo json_encode(array('result' =>$tpl->fetch()));
exit;