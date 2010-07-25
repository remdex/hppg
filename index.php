<?php

$star_microtile = microtime();
function set_time ( $start_time, $end_time )
{
	$start = explode(' ', $start_time);
	$end = explode(' ', $end_time);
	return  $time = $end[0] + $end[1] - $start[0] - $start[1];
}
/* DEBUG END */

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);

require_once "ezcomponents/Base/src/base.php"; // dependent on installation method, see below

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( './lib','./lib/autoloads'); 

// your code here
ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);
  
erLhcoreClassSystem::init();

$url = erLhcoreClassURL::getInstance();
if (!is_null($url->getParam( 'module' )) && file_exists('modules/lh'.$url->getParam( 'module' ).'/module.php')){
    $ModuleToRun = $url->getParam( 'module' );
    $ViewToRun = $url->getParam( 'function' );
} else {	
	$cfg = erConfigClassLhConfig::getInstance();
    $params = $cfg->getOverrideValue('site','default_url');
    
    $ModuleToRun = $params['module'];
	$ViewToRun = $params['view'];
}
    
include_once('modules/lh'.$ModuleToRun.'/module.php');

$cfgSite = erConfigClassLhConfig::getInstance();

if ($cfgSite->conf->getSetting( 'site', 'redirect_mobile' ) !== false && (!isset($_COOKIE['RegularVersion'])  && preg_match("/http_(x_wap|ua)_(.*?)/i",implode(' ',array_keys($_SERVER)))) || ( isset($_COOKIE['RegularVersion']) && $_COOKIE['RegularVersion'] == 2 ) ){
	erLhcoreClassSystem::instance()->MobileDevice = true;	
	$optionsSiteAccess = $cfgSite->conf->getSetting('site_access_options',$cfgSite->conf->getSetting( 'site', 'redirect_mobile' ));		
	erLhcoreClassSystem::instance()->Language = $optionsSiteAccess['locale'];                         
    erLhcoreClassSystem::instance()->ThemeSite = $optionsSiteAccess['theme'];                         
    erLhcoreClassSystem::instance()->WWWDirLang = '/'.$cfgSite->conf->getSetting( 'site', 'redirect_mobile' ); 
    erLhcoreClassSystem::instance()->SiteAccess = $cfgSite->conf->getSetting( 'site', 'redirect_mobile' ); 
    setcookie('RegularVersion','2',time()+30*24*3600,"/");      // Mobile version   
} elseif (!isset($_COOKIE['RegularVersion'])){     
        setcookie('RegularVersion','1',time()+30*24*3600,"/");  // Regular version
}

$Result = erLhcoreClassModule::runModule($ViewList,$FunctionList);

$tpl = erLhcoreClassTemplate::getInstance('pagelayouts/main.php');
$tpl->set('Result',$Result);
if (isset($Result['pagelayout']))
{
	$tpl->setFile('pagelayouts/'.$Result['pagelayout'].'.php');
}

echo $tpl->fetch();
   
?>