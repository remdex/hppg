<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/ownercategorys.tpl.php'); 

$tpl->set('owner_id',(int)$Params['user_parameters']['owner_id']);
    
$Result['content'] = $tpl->fetch();
$path = array();


?>