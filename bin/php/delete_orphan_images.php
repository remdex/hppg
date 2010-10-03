<?php

ini_set("max_execution_time", "9600");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once "./ezcomponents/Base/src/base.php";

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( './', './lib/autoloads'); 


ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);


$input = new ezcConsoleInput();

$helpOption = $input->registerOption(
    new ezcConsoleOption(
        's',
        'siteaccess',
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

$siteAccessName = 'site_admin';
if ( !$helpOption->value === false )
{
    $siteAccessName = $helpOption->value;
} 


try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}

$instance = erLhcoreClassSystem::instance();
$instance->SiteAccess = $siteAccessName; 
$instance->SiteDir = './';
$cfgSite = erConfigClassLhConfig::getInstance();    
$defaultSiteAccess = $cfgSite->conf->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->conf->getSetting('site_access_options',$siteAccessName);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$siteAccessName;   

// Find all images, using iterator here.
$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
$q->orderBy('pid ASC' ); 
    
$objects = $session->findIterator( $q, 'erLhcoreClassModelGalleryImage' );       

$output = new ezcConsoleOutput();
$status = new ezcConsoleProgressMonitor( $output, erLhcoreClassModelGalleryImage::getImageCount( ) );

foreach ($objects as $object)
{
    $photoPath = 'albums/'.$object->filepath;
    
    if (!file_exists($photoPath.$object->filename) || !is_file($photoPath.$object->filename)) {        
        $status->addEntry( 'ACTION', "Removing orphan image - #{$object->pid}." );
        $orphanImage = erLhcoreClassModelGalleryImage::fetch($object->pid); // I think that way is safer, than with iterator...
        $orphanImage->removeThis();        
    } else {
        $status->addEntry( 'ACTION', "Image original does exists - #{$object->pid}." );
    }
}

$output->outputLine();