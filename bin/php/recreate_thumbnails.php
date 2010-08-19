<?php

ini_set("max_execution_time", "9600");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once "./ezcomponents/Base/src/base.php";

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( './lib', './lib/autoloads'); 


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
 
$targetOption = $input->registerOption(
    new ezcConsoleOption(
        't',
        'target',
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

if ( $targetOption->value === false )
{
    echo "Parameter -{$targetOption->short}/--{$targetOption->long} was not submitted. It can be \nsmall - will be recreated small thumbnails\nnormal - will be recreated normal size thumbnails\nboth - will be recreated small and normal images.\n";
    exit;
} elseif (!in_array($targetOption->value,array('small','normal','both'))) {
    echo "Parameter -{$targetOption->short}/--{$targetOption->long} was submitted with wrong argument. It can be \nsmall - will be recreated small thumbnails\nnormal - will be recreated normal size thumbnails\nboth - will be recreated small and normal images.\n";
    exit;
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
$instance->SiteAccess = $helpOption->value; 
$instance->SiteDir = './';
$cfgSite = erConfigClassLhConfig::getInstance();    
$defaultSiteAccess = $cfgSite->conf->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->conf->getSetting('site_access_options',$helpOption->value);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$helpOption->value;   

// Find all images, using iterator here.
$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
$objects = $session->findIterator( $q, 'erLhcoreClassModelGalleryImage' );       

$output = new ezcConsoleOutput();
$status = new ezcConsoleProgressMonitor( $output, erLhcoreClassModelGalleryImage::getImageCount() );


foreach ($objects as $object)
{
    $photoPath = 'albums/'.$object->filepath;
    
    try {
        if (file_exists($photoPath.$object->filename) && is_file($photoPath.$object->filename)){ 
                          
            if ($targetOption->value == 'small' || $targetOption->value == 'both') {
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoPath.$object->filename, $photoPath.'/thumb_'.$object->filename ); 
                $status->addEntry( 'ACTION', "Recreating small size image thumbnail PID #{$object->pid}." );
                chmod($photoPath.'/thumb_'.$object->filename,$cfgSite->conf->getSetting( 'site', 'StorageFilePermissions' ));
            }  
                        
            if ($targetOption->value == 'normal' || $targetOption->value == 'both') {
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoPath.$object->filename, $photoPath.'/normal_'.$object->filename ); 
                $status->addEntry( 'ACTION', "Recreating normal size image thumbnail PID #{$object->pid}." );
                chmod($photoPath.'/normal_'.$object->filename,$cfgSite->conf->getSetting( 'site', 'StorageFilePermissions' ));
            }
            
        } else {
            $status->addEntry( 'ACTION', "Original file not found #{$object->pid}." );
        }
    } catch (Exception $e){
        $status->addEntry( 'ACTION', "Cound not convert - #{$object->pid}." );
    }
}

$output->outputLine();


