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

$palletePathOption = $input->registerOption(
    new ezcConsoleOption(
        'p',
        'pallete',
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

$palletePath = 'doc/color_palletes/color_palette.png';
if ( !$palletePathOption->value === false )
{
    $palletePath = $palletePathOption->value;
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


$img = imagecreatefrompng($palletePath);
list($width,$height) = getimagesize($palletePath);

$db = ezcDbInstance::get();        		
$stmt = $db->prepare("TRUNCATE TABLE `lh_gallery_pallete`");	
$stmt->execute();

for ($i = 1; $i < $width;$i++) {
    for ($n = 1; $n < $height;$n++) {
        
        $thisColor = imagecolorat($img, $i, $n); 
        $rgb = imagecolorsforindex($img, $thisColor); 
         
        if (erLhcoreClassModelGalleryPallete::getListCount(array('filter' => array('red' => $rgb['red'],'green' => $rgb['green'],'blue' => $rgb['blue']))) == 0)
        {        
            $pallete = new erLhcoreClassModelGalleryPallete();
            $pallete->red = $rgb['red'];
            $pallete->green = $rgb['green'];
            $pallete->blue = $rgb['blue'];
            $pallete->saveThis();
        }
    }
}

echo "Pallete generated\n";