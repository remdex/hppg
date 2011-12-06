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
 
$targetOption = $input->registerOption(
    new ezcConsoleOption(
        't',
        'target',
        ezcConsoleInput::TYPE_STRING 
    )
); 

$pidOption = $input->registerOption(
    new ezcConsoleOption(
        'p',
        'pid',
        ezcConsoleInput::TYPE_INT 
    )
);


$photoidOption = $input->registerOption(
    new ezcConsoleOption(
        'id',
        'photoid',
        ezcConsoleInput::TYPE_INT 
    )
); 

$albumOption = $input->registerOption(
    new ezcConsoleOption(
        'a',
        'aid',
        ezcConsoleInput::TYPE_INT 
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
$defaultSiteAccess = $cfgSite->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->getSetting('site_access_options',$siteAccessName);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$siteAccessName;   

// Find all images, using iterator here.
$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
$q->orderBy('pid ASC' ); 

$filter = array();
$filterExpresion = array();

if ($pidOption->value !== false){
    $filter['filtergt'] = array('pid' => $pidOption->value);
    $filterExpresion[] =  $q->expr->gt( 'pid', $q->bindValue($pidOption->value) );    
}

if ($albumOption->value !== false){ 
    $filter['filter'] = array('aid' => $albumOption->value);
    $filterExpresion[] =  $q->expr->eq( 'aid', $q->bindValue($albumOption->value) );    
}

if ($photoidOption->value !== false) {
    $filter['filter'] = array('pid' => $photoidOption->value);
    $filterExpresion[] =  $q->expr->eq( 'pid', $q->bindValue($photoidOption->value) );    
}

if (count($filterExpresion) > 0){
    $q->where( 
       $filterExpresion
    );
}
    
$objects = $session->findIterator( $q, 'erLhcoreClassModelGalleryImage' );       

$output = new ezcConsoleOutput();
$status = new ezcConsoleProgressMonitor( $output, erLhcoreClassModelGalleryImage::getImageCount( $filter ) );

foreach ($objects as $object)
{
    $photoPath = 'albums/'.$object->filepath;
    
    try {
        if (file_exists($photoPath.$object->filename) && is_file($photoPath.$object->filename)){ 
             
            if ($object->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE) 
            {   
                $recreating = '';
                if ($targetOption->value == 'small') {
                    $recreating = 'small';
                } elseif ($targetOption->value == 'normal'){
                    $recreating = 'normal';
                } elseif ($targetOption->value == 'both'){
                    $recreating = 'small and normal';
                }
                
                $status->addEntry( 'ACTION', "Recreating {$recreating} size image thumbnail PID #{$object->pid}." );
                       
                if ($targetOption->value == 'small' || $targetOption->value == 'both') {
                    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoPath.$object->filename, $photoPath.'/thumb_'.$object->filename );                 
                    chmod($photoPath.'/thumb_'.$object->filename,$cfgSite->getSetting( 'site', 'StorageFilePermissions' ));
                }  
                            
                if ($targetOption->value == 'normal' || $targetOption->value == 'both') {
                    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoPath.$object->filename, $photoPath.'/normal_'.$object->filename );                
                    chmod($photoPath.'/normal_'.$object->filename,$cfgSite->getSetting( 'site', 'StorageFilePermissions' ));
                }
            } else {
                $status->addEntry( 'ACTION', "Skipping multipedia type PID #{$object->pid}." );
            }
            
        } else {
            $status->addEntry( 'ACTION', "Original file not found #{$object->pid}." );
        }
    } catch (Exception $e){
        $status->addEntry( 'ACTION', "Cound not convert - #{$object->pid}. MSG: ".$e->getMessage() );
    }
}

$output->outputLine();


