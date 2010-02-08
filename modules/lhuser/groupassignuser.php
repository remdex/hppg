<?php


$tpl = erLhcoreClassTemplate::getInstance('lhuser/groupassignuser.tpl.php');
$tpl->set('group_id',(int)$Params['user_parameters']['group_id']);

echo $tpl->fetch();
exit;