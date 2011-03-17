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
 
$widthOption = $input->registerOption(
    new ezcConsoleOption(
        'x',
        'width',
        ezcConsoleInput::TYPE_INT 
    )
); 

$heightOption = $input->registerOption(
    new ezcConsoleOption(
        'y',
        'height',
        ezcConsoleInput::TYPE_INT 
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

/**
 * Three options:
 * original - apply to original
 * both - apply to both
 * 
 * @desc If this option is sete @wf (watermark_file) must be set
 * 
 * 
 * */
$watermarkOption = $input->registerOption(
    new ezcConsoleOption(
        'w',
        'watermark',
        ezcConsoleInput::TYPE_STRING 
    )
); 

$watermarkFileOption = $input->registerOption(
    new ezcConsoleOption(
        'wf',
        'watermark_file',
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

if ( $widthOption->value === false || $widthOption->value <= 0)
{
    echo "Parameter -{$widthOption->short}/--{$widthOption->long} was not submitted. It can be \n maximum width of desirable resolution.\n";
    exit;
}

if ( $heightOption->value === false || $heightOption->value <= 0)
{
    echo "Parameter -{$heightOption->short}/--{$heightOption->long} was not submitted. It can be \n maximum height of desirable resolution.\n";
    exit;
}

if ( $watermarkOption->value !== false && !in_array($watermarkOption->value,array('original','both')))
{
    echo "Parameter -{$watermarkOption->short}/--{$watermarkOption->long} must be set to one of (original,normal,both).\n";
    exit;
}


if ( $watermarkOption->value !== false && !file_exists($watermarkFileOption->value))
{
    echo "Watermark path was not submited.\n";
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
$defaultSiteAccess = $cfgSite->conf->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->conf->getSetting('site_access_options',$siteAccessName);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$siteAccessName;   

// Find all images, using iterator here.
$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
$q->orderBy('pid DESC' ); 

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

/* Conversion settings */
$conversionSettings = array();
       
if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'imagemagic_enabled' ) == true)
{
   $conversionSettings[] = new ezcImageHandlerSettings( 'imagemagick', 'erLhcoreClassGalleryImagemagickHandler' );
}

$conversionSettings[] =  new ezcImageHandlerSettings( 'gd','erLhcoreClassGalleryGDHandler' );

$converter = new ezcImageConverter(
        new ezcImageConverterSettings(
            $conversionSettings
        )
);

$filterNormal = array();            
$filterNormal[] = new ezcImageFilter( 
            'scale',
            array( 
                'width'     => (int)$widthOption->value,                        
                'height'     => (int)$heightOption->value,                        
                'direction' => ezcImageGeometryFilters::SCALE_DOWN,
            )
);
      

if ($watermarkOption->value !== false)
{  
    $waterMarkFilter = new ezcImageFilter(
    'watermarkCenterAbsolute',
    	array(
        	'image' => $watermarkFileOption->value,
        	'posX' => 0,
        	'posY' => 0,
    	)
    );
    $filterNormal[] = $waterMarkFilter;
}

$converter->createTransformation(
                'originalscale',
                $filterNormal,
                array( 
                    'image/jpeg',
                    'image/png',
                ),
                new ezcImageSaveOptions(array('quality' => (int)erLhcoreClassModelSystemConfig::fetch('full_image_quality')->current_value))
            );  
            
                            
foreach ($objects as $object)
{     
    $photoPath = 'albums/'.$object->filepath;
    
    try {
        if (file_exists($photoPath.$object->filename) && is_file($photoPath.$object->filename)){ 
             
            if ($object->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE) 
            {                                  
                $converter->transform( 'originalscale', $photoPath.$object->filename, $photoPath.$object->filename );
                chmod($photoPath.$object->filename,$cfgSite->conf->getSetting( 'site', 'StorageFilePermissions' ));
                                
                if ($watermarkOption->value == 'both') {
                    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoPath.$object->filename, $photoPath.'/normal_'.$object->filename );
                    chmod($photoPath.'/normal_'.$object->filename,$cfgSite->conf->getSetting( 'site', 'StorageFilePermissions' ));
                }
                
                // Update image data
                $imageAnalyze = new ezcImageAnalyzer( $photoPath.$object->filename ); 	      
                $object->pwidth = $imageAnalyze->data->width;
                $object->pheight = $imageAnalyze->data->height;
                $object->filesize = filesize($photoPath.$object->filename);
                $object->total_filesize = filesize($photoPath.$object->filename)+filesize($photoPath.'/thumb_'.$object->filename)+filesize($photoPath.'/normal_'.$object->filename);
                $session->update($object);
                
                $status->addEntry( 'ACTION', "Scaling original PID #{$object->pid}." );
                
                                
                                
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
// php ./bin/php/scale_originals.php -s site_admin -x 500 -y 500 -a 71
$output->outputLine();


