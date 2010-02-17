<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/publicupload.tpl.php');
$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Public images upload')));
$Result['content'] = $tpl->fetch();
$Result['additional_js'] = '<script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/swfupload.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.swfobject.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.cookies.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.queue.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.speed.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/fileprogress.js').'"></script>
                            <script type="text/javascript" language="javascript" src="'.erLhcoreClassDesign::design('js/swfupload/plugins/handlers.js').'"></script>';