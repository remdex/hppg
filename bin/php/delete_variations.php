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
 
$directoryOption = $input->registerOption(
    new ezcConsoleOption(
        'd',
        'directory',
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
$output = new ezcConsoleOutput();

$output->formats->success->color   = 'green';

$output->outputText( "Directory base - {$directoryOption->value}\n", 'success');
$listAlbums = erLhcoreClassGalleryBatch::listDirectory($directoryOption->value);

foreach ($listAlbums as $dir) {
    
  
    $images = ezcBaseFile::findRecursive(
		$dir,
		array( '@(.*)$@' )
	);
		
    if (count($images) > 0) {        
        $output->outputText( "Cleaning directory - {$dir}\n");       
                         
        foreach ($images as $imagePath) {
            
            try {
                
                $photoDir = dirname($imagePath);
                $fileName = basename($imagePath);
                             
                if ((preg_match('/^(normal_|.normal_|thumb_|.thumb_)/i',basename($fileName)))) {  
                       
                    if (file_exists($photoDir.'/'.$fileName) || file_exists($photoDir.'/'.$fileName)) { 
                       unlink($photoDir.'/'.$fileName);
            	       $output->outputText( "Deleting image - {$imagePath}\n"); 
                    } else {               
                       //$output->outputText( "Skiping image - {$imagePath}\n");
                    }
                } else {               
                       //$output->outputText( "Skiping image - {$imagePath}\n");
                }
            } catch (Exception $e) {                          
              
            } 
        } 
       
        echo $output->outputText('Memory usage - '. round((memory_get_usage()/1024/1024),2)."\n", 'success');    
    }    
}

$output->outputText( "Cleanup finished \n"); 