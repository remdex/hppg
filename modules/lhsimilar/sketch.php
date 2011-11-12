<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/sketch.tpl.php');
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('similar/sketch','Sketch and find images by your sketch')));
$Result['path_base'] = erLhcoreClassDesign::baseurldirect('similar/sketch');