<?php

ini_set("max_execution_time", "0");
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

$currentLastPid = 0;
foreach ($objects as $object)
{
    echo "Indexing image -> ",$object->pid,"\n";
    $currentLastPid = $object->pid;
    erLhcoreClassPalleteIndexImage::indexImage($object);
    erLhcoreClassModelGallerySphinxSearch::updateColorAttribute($object);
}

// Update last indexed status
$db = ezcDbInstance::get(); 
$stmt = $db->prepare("SELECT MAX(pid) as last_index_image FROM lh_gallery_pallete_images");
$stmt->execute();
$lastIndex = (int)$stmt->fetchColumn();

if ($currentLastPid > $lastIndex) {
    erLhcoreClassPalleteIndexImage::setLastIndex('image_index',$currentLastPid);
}

