<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhstatistic/index.tpl.php');

// Here we go. Zend framework
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_Docs');

function getAuthSubUrl($scope) {
    $next = 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('statistic/authanalytics/');
    $secure = false;
    $session = true;
    return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
}

$authSubUrlAnalytics = getAuthSubUrl('https://www.google.com/analytics/feeds/');
$tpl->set('auth_url_analytics',$authSubUrlAnalytics);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('statistic/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Statistic')),
					  array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Home')));
?>