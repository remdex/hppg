<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsimilar/sketch.tpl.php');
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurldirect('similar/sketch'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('similar/sketch','Sketch and find images by your sketch')));
$Result['path_base'] = erLhcoreClassDesign::baseurldirect('similar/sketch');