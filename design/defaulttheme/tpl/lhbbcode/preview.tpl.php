<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>markItUp! preview template</title>
<link rel="stylesheet" href="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::designCSS('js/markitup/templates/preview.css');?>" /> 
</head>
<body>
<?php echo $content;?>
</body>
</html>