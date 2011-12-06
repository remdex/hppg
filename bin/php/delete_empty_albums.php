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

$categoryOption = $input->registerOption(
    new ezcConsoleOption(
        'c',
        'category',
        ezcConsoleInput::TYPE_INT 
    )
);

$actionOption = $input->registerOption(
    new ezcConsoleOption(
        'a',
        'action',
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

if ( $categoryOption->value === false )
{
    echo "Please provide what category to remove -c or --category\n";
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
$instance->SiteAccess = $siteAccessName; 
$instance->SiteDir = './';
$cfgSite = erConfigClassLhConfig::getInstance();    
$defaultSiteAccess = $cfgSite->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->getSetting('site_access_options',$siteAccessName);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$siteAccessName;   

$db = ezcDbInstance::get();        		
$stmt = $db->prepare("SELECT lh_gallery_albums.aid,count(lh_gallery_images.pid) as images_count FROM lh_gallery_albums LEFT JOIN lh_gallery_images ON lh_gallery_images.aid = lh_gallery_albums.aid WHERE lh_gallery_albums.category = :category_id GROUP BY  lh_gallery_albums.aid HAVING images_count = 0");	
$stmt->bindValue('category_id',$categoryOption->value);
$stmt->execute();
$albums = $stmt->fetchAll();

$output = new ezcConsoleOutput();
foreach ($albums as $album) {
    if ($actionOption->value == 'remove'){
        $output->outputText( "Removing album - {$album['aid']}\n"); 
        $album = erLhcoreClassModelGalleryAlbum::fetch($album['aid']);
        $album->removeThis();
    } else {
        $output->outputText( "Possible removement - {$album['aid']}\n"); 
    }
}

if (count($albums) == 0) {
    echo "No albums found for removement.\n";
}

//Usage Ex. php ./bin/php/delete_empty_albums.php -c 28 -a remove
