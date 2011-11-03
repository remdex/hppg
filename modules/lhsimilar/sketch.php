<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/sketch.tpl.php');
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => 'Sketch and find images by your sketch'));