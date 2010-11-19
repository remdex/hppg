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

$apiOption = $input->registerOption(
    new ezcConsoleOption(
        'a',
        'api',
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

$apiKey = false;
if ( !$apiOption->value === false )
{
    $apiKey = $apiOption->value;
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
array( '@/module\.php$@' ),
array( '@/albums|ezcomponents|lhcaptcha|var|extension|cache|bin|Zend|xhprof_html|xhprof_lib|translations|setttings|pos/@' ));

$arrayTranslationsProcess = array();
$modulesNames = array();

foreach ($filesToCheck as $filePath)
{
    include($filePath);   
    $moduleNameParts = explode('/',$filePath);
    array_pop($moduleNameParts);// Remove module.php name
    $module = array_pop($moduleNameParts);// Module Name
    $modulesNames[preg_replace('/^lh/','',$module)] = $ViewList; 
}  


function translateToLanguage($apiKey,$toLanguage, $string) {
    
    static $cacheTranslations = array();
    
    if ($apiKey !== false){
        
        if (key_exists(md5($string),$cacheTranslations)) return $cacheTranslations[md5($string)];
        
        $string = urlencode($string);
        $response = file_get_contents("https://www.googleapis.com/language/translate/v2?key={$apiKey}&q={$string}&source=en&target=".$toLanguage);
        
        $data = json_decode($response,true);
                        
        if (isset($data['data']['translations'][0]['translatedText']))
        {
            $cacheTranslations[md5($string)] = $data['data']['translations'][0]['translatedText'];
            return $cacheTranslations[md5($string)];
        } else {
            print_r($data);
        }
    }
    
    return '';
}


$moduleNamesTranslated = array();
$moduleNamesTranslatedViews = array();

foreach ($modulesNames as $name => $moduleViews) {
    
      $nameModuleTranslated = erLhcoreClassCharTransform::TransformToURL(translateToLanguage($apiKey,substr($locale,0,2),$name));
      $moduleNamesTranslated[$nameModuleTranslated] = $name;
    
    foreach (array_keys($moduleViews) as $viewName)
    {
        $moduleNamesTranslatedViews[$name][erLhcoreClassCharTransform::TransformToURL(translateToLanguage($apiKey,substr($locale,0,2),$viewName))] = $viewName;
    } 
}


$content = "<?php \n\$moduleTranslations = ".var_export($moduleNamesTranslated,true).";\n\n";
$content .=  '$moduleViewTranslations = '.var_export($moduleNamesTranslatedViews,true).";\n\n";

file_put_contents('translations/'.$locale.'/translations.php',$content);



//print_r($moduleNamesTranslated);

/*
$reader = new ezcTranslationTsBackend( 'translations/'.$locale );
$reader->setOptions( array( 'format' => 'translation.xml' ) );
$reader->initReader( $locale );

$manager = new ezcTranslationManager( $reader );

function translateToLanguage($apiKey,$toLanguage, $string) {
    
    static $cacheTranslations = array();
    
    if ($apiKey !== false){
        
        if (key_exists(md5($string),$cacheTranslations)) return $cacheTranslations[md5($string)];
        
        $string = urlencode($string);
        $response = file_get_contents("https://www.googleapis.com/language/translate/v2?key={$apiKey}&q={$string}&source=en&target=".$toLanguage);
        
        $data = json_decode($response,true);
                        
        if (isset($data['data']['translations'][0]['translatedText']))
        {
            $cacheTranslations[md5($string)] = $data['data']['translations'][0]['translatedText'];
            return $cacheTranslations[md5($string)];
        } else {
            print_r($data);
        }
    }
    
    return '';
}

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
                    $contextItems[] = new ezcTranslationData( $string, translateToLanguage($apiKey,substr($locale,0,2),$string), NULL, ezcTranslationData::UNFINISHED );
                 }
                 
           } catch (Exception $e) { // Translation does not exist
                $contextItems[] = new ezcTranslationData( $string, translateToLanguage($apiKey,substr($locale,0,2),$string), NULL, ezcTranslationData::UNFINISHED );
           }
       } else {
           $contextItems[] = new ezcTranslationData( $string, '', NULL, ezcTranslationData::UNFINISHED );
       }
    }

    $reader->initWriter( $locale );    
    $reader->storeContext( $context, $contextItems );    
    $reader->deinitWriter();
}*/


