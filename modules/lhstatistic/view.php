<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhstatistic/view.tpl.php');

$cache = CSCacheAPC::getMem(); 
$cacheVersion = $cache->getCacheVersion('analytics_cache_version');
if (($AnalyticsCacheData = $cache->restore(md5($cacheVersion.'analytics_data'.erLhcoreClassSystem::instance()->SiteAccess))) === false)
{
	$tplCache = new erLhcoreClassTemplate( 'lhstatistic/analytics_parsed.tpl.php');
	
	// Here we go. Zend framework
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Http_Client');
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_AuthSub');
	Zend_Loader::loadClass('Zend_Gdata_Analytics');
	
	$client = Zend_Gdata_AuthSub::getHttpClient(erLhcoreClassModelSystemConfig::fetch('google_analytics_token')->current_value);
	$analytics = new Zend_Gdata_Analytics($client);
	
	// Current month
	$query = $analytics->newDataQuery() 
	    ->setProfileId(erLhcoreClassModelSystemConfig::fetch('google_analytics_site_profile_id')->current_value) 
	    ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)  
	    ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS) 
	    ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE) 
	    ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_DAY)
	    ->setStartDate(date('Y-m').'-01')  
	    ->setEndDate(date('Y-m-d'))  
	    ->setSort(Zend_Gdata_Analytics_DataQuery::DIMENSION_DAY, true)
	    ->setMaxResults(31); 
	        
	$result = $analytics->getDataFeed($query); 
	 
	// Last month
	$query = $analytics->newDataQuery() 
	    ->setProfileId(erLhcoreClassModelSystemConfig::fetch('google_analytics_site_profile_id')->current_value) 
	    ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)  
	    ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS) 
	    ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE) 
	    ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_DAY)
	    ->setStartDate(date('Y-m',mktime(0,0,0,date('m')-1,1,date('Y'))).'-01')  
	    ->setEndDate(date('Y-m-t',mktime(0,0,0,date('m')-1,1,date('Y'))))  
	    ->setSort(Zend_Gdata_Analytics_DataQuery::DIMENSION_DAY, true)
	    ->setMaxResults(31-date('d')); 
	$resultLast = $analytics->getDataFeed($query);  
	$AnalyticsCacheData['data'] = $result;
	$AnalyticsCacheData['data_last'] = $resultLast;
	
	$tplCache->set('data', $result); 
	$tplCache->set('data_last', $resultLast); 
	$dataParsed = $tplCache->fetch();	
	$cache->store(md5($cacheVersion.'analytics_data'.erLhcoreClassSystem::instance()->SiteAccess),$dataParsed,600);
	
	$tpl->set('dataParsed', $dataParsed);
	
} else {	
	$tpl->set('dataParsed', $AnalyticsCacheData); 
}


$Result['content'] = $tpl->fetch();

if (erLhcoreClassSystem::instance()->SiteAccess == 'site_admin'){
	$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('statistic/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Statistic')),
					  array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','View statistic')));
} else {
	$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','View statistic')));
}

?>