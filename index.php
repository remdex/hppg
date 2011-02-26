<?php


//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

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

ezcBase::addClassRepository( './','./lib/autoloads'); 

// your code here
ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);
  
erLhcoreClassSystem::init();
$Result = erLhcoreClassModule::moduleInit();

$tpl = erLhcoreClassTemplate::getInstance('pagelayouts/main.php');
$tpl->set('Result',$Result);
if (isset($Result['pagelayout']))
{
	$tpl->setFile('pagelayouts/'.$Result['pagelayout'].'.php');
}

echo $tpl->fetch();
   


/*include_once 'xhprof_lib/utils/xhprof_lib.php';
include_once 'xhprof_lib/utils/xhprof_runs.php';

$profiler_namespace = 'myapp';  // namespace for your application
$xhprof_data = xhprof_disable();

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);

// url to the XHProf UI libraries (change the host name and path)
$profiler_url = sprintf('/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';*/