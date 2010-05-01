<?php

ini_set('error_reporting', E_ALL);
ini_set('register_globals', 0);
ini_set('display_errors', 1);

ini_set("max_execution_time", "3600");


//require_once "./ezcomponents/Base/src/base.php";
require_once dirname(__FILE__)."/ezcomponents/Base/src/base.php";

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

//ezcBase::addClassRepository( './lib','./lib/autoloads'); 
ezcBase::addClassRepository( dirname(__FILE__).'/lib', dirname(__FILE__).'/lib/autoloads'); 

$input = new ezcConsoleInput();

$helpOption = $input->registerOption(
new ezcConsoleOption(
    'l',
    'language',
    ezcConsoleInput::TYPE_STRING 
)
); 

try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
} 



ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);

$instance = erLhcoreClassSystem::instance();
$instance->SiteDir = dirname(__FILE__).'/';
$instance->Language = $helpOption->value;


include_once('modules/lhgallery/cron.php');



?>