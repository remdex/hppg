<?php



$star_microtile = microtime();
function set_time ( $start_time, $end_time )
{
	$start = explode(' ', $start_time);
	$end = explode(' ', $end_time);
	return  $time = $end[0] + $end[1] - $start[0] - $start[1];
}
/* DEBUG END */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

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
	/*First page search results*/
	$ModuleToRun = 'gallery';
	$ViewToRun = 'index';
}
    
include_once('modules/lh'.$ModuleToRun.'/module.php');

$Result = erLhcoreClassModule::runModule($ViewList,$FunctionList);

$tpl = erLhcoreClassTemplate::getInstance('pagelayouts/main.php');
$tpl->set('Result',$Result);
if (isset($Result['pagelayout']))
{
	$tpl->setFile('pagelayouts/'.$Result['pagelayout'].'.php');
}

echo $tpl->fetch();

?>