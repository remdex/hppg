<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/publicupload.tpl.php');
$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Public images upload')));
$Result['content'] = $tpl->fetch();
$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/fileuploader.js').'"></script>';