<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/sketch.tpl.php');
$Result['content'] = $tpl->fetch();
$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/sketch.min.js').'"></script>';
$path = array(array('title' => 'Sketch and find images by your sketch'));