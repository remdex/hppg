<?php

ini_set("max_execution_time", "9600");
ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);

require_once "./ezcomponents/Base/src/base.php";

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( '.', './lib/autoloads'); 

ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);

$input = new ezcConsoleInput();

$helpOption = $input->registerOption(
    new ezcConsoleOption(
        'l',
        'locale',
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

$locale = 'en_EN';
if ( !$helpOption->value === false )
{
    $locale = $helpOption->value;
} 

try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}

$filesToCheck = ezcBaseFile::findRecursive('.',
array( '@\.php$@' ),
array( '@/albums|ezcomponents|lhcaptcha|var|extension|cache|bin|Zend|xhprof_html|xhprof_lib|translations|setttings|pos/@' ));

$arrayTranslationsProcess = array();

foreach ($filesToCheck as $filePath)
{
    $contentFile = file_get_contents($filePath);
    
    $Matches = array();
	preg_match_all('/<\?=erTranslationClassLhTranslation::getInstance\(\)->getTranslation\(\'(.*?)\',\'(.*?)\'\)(.*?)\?\>/i',$contentFile,$Matches);

	foreach ($Matches[1] as $key => $section)
	{
	    if (!isset($arrayTranslationsProcess[$section])) {
	        $arrayTranslationsProcess[$section] = array();
	    }
	    
	    if (!in_array($Matches[2][$key],$arrayTranslationsProcess[$section])){
	        $arrayTranslationsProcess[$section][] = $Matches[2][$key];
	    }
	    
	    $contentFile = str_replace($Matches[0][$key],'',$contentFile);		    
	}
	
	$Matches = array();
	preg_match_all('/erTranslationClassLhTranslation::getInstance\(\)->getTranslation\(\'(.*?)\',\'(.*?)\'\)/i',$contentFile,$Matches);
				
	foreach ($Matches[1] as $key => $section)
	{
	    if (!isset($arrayTranslationsProcess[$section])) {
	        $arrayTranslationsProcess[$section] = array();
	    }
	    
	    if (!in_array($Matches[2][$key],$arrayTranslationsProcess[$section])){
	        $arrayTranslationsProcess[$section][] = $Matches[2][$key];
	    }
	}   
}  


$reader = new ezcTranslationTsBackend( 'translations/'.$locale );
$reader->setOptions( array( 'format' => 'translation.xml' ) );
$reader->initReader( $locale );

$manager = new ezcTranslationManager( $reader );

foreach ($arrayTranslationsProcess as $context => $itemsToTranslate)
{       
    $contextItems = array() ;
    
    try {
        $contextItem = $manager->getContext( $locale, $context );                        
    } catch (Exception $e) { // Context does not exists          
        $reader->initWriter( $locale );
        $reader->storeContext( $context, $contextItems );
        $reader->deinitWriter();        
        $contextItem = $manager->getContext( $locale, $context );
    }
    
    foreach ($itemsToTranslate as $string)
    {   
       if ($locale != 'en_EN') {
           try {
                 $originalTranslation = $contextItem->getTranslation($string);
                 
                 if ($originalTranslation != ''){
                    $contextItems[] = new ezcTranslationData( $string, $originalTranslation, NULL, ezcTranslationData::TRANSLATED );
                 } else {
                    $contextItems[] = new ezcTranslationData( $string, '', NULL, ezcTranslationData::UNFINISHED );
                 }
                 
           } catch (Exception $e) { // Translation does not exist
                $contextItems[] = new ezcTranslationData( $string, '', NULL, ezcTranslationData::UNFINISHED );
           }
       } else {
           $contextItems[] = new ezcTranslationData( $string, '', NULL, ezcTranslationData::UNFINISHED );
       }
    }

    $reader->initWriter( $locale );    
    $reader->storeContext( $context, $contextItems );    
    $reader->deinitWriter();
}


