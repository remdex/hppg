<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhstatistic/choosesite.tpl.php');


if (isset($_POST['ProfileID']))
{
	if ($_POST['ProfileID'] > 0) {		
		$analyticsKey = erLhcoreClassModelSystemConfig::fetch('google_analytics_site_profile_id');
		$analyticsKey->value = $_POST['ProfileID'];
		erLhcoreClassSystemConfig::getSession()->update($analyticsKey);	
			
		$cache = CSCacheAPC::getMem(); 
		$cache->increaseCacheVersion('analytics_cache_version');			
		erLhcoreClassModule::redirect('statistic/view');
	}
} 


// Here we go. Zend framework
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_Docs');
Zend_Loader::loadClass('Zend_Gdata_Analytics');

$client = Zend_Gdata_AuthSub::getHttpClient(erLhcoreClassModelSystemConfig::fetch('google_analytics_token')->current_value);
$analytics = new Zend_Gdata_Analytics($client);

$accounts = $analytics->getAccountFeed(); 
$tpl->set('accounts',$accounts);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('statistic/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Statistic')),
					  array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Site choose')));

?>