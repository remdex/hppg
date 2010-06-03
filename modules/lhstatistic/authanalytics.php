<?php

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');

$sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);

$analyticsKey = erLhcoreClassModelSystemConfig::fetch('google_analytics_token');
$analyticsKey->value = $sessionToken;
erLhcoreClassSystemConfig::getSession()->update($analyticsKey);

erLhcoreClassModule::redirect('statistic/choosesite');
return ;
