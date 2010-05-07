<?php
/**
 * Main part of code from :
 * http://www.massassi.com/php/articles/template_engines/
 * 
 * Modified by remdex
 * */

class erLhcoreClassTemplate {
    var $vars; /// Holds all the template variables
 
    private static $instance = null;   
    private $cacheWriter = null;
    private $cacheTemplates = array() ;
    private $cacheEnabled = true;
    private $templatecompile = true;
    
    var $file = null;
        
    public static function getInstance($file = null)  
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erLhcoreClassTemplate($file);            
        } else {
        	self::$instance->setFile($file);
        	self::$instance->vars = array();
        }
        
        return self::$instance;
    }
    
    /**
     * Constructor
     *
     * @param $file string the file name you want to load
     */
    function erLhcoreClassTemplate($file = null) {
        
        $cfg = erConfigClassLhConfig::getInstance();
        $sys = erLhcoreClassSystem::instance()->SiteDir;        
        $this->cacheEnabled = $cfg->conf->getSetting( 'site', 'templatecache' );
        $this->templatecompile = $cfg->conf->getSetting( 'site', 'templatecompile' );
        
        if (!is_null($file))
        $this->file = $file;   
             
        $this->cacheWriter = new ezcCacheStorageFileArray($sys . 'cache/cacheconfig/');    
        
        if (($this->cacheTemplates = $this->cacheWriter->restore('templateCache')) == false)
        {        	
        	$this->cacheWriter->store('templateCache',array());
        	$this->cacheTemplates = array();
        }    
    }

    /**
     * Set a template variable.
     */
    function set($name, $value) {
        $this->vars[$name] = (is_object($value) && get_class($value) == 'cachedtemplate') ? $value->fetch() : $value;
    }

    /**
     * Set's template file
     * */
    function setFile($file)
    {
       $cfg = erConfigClassLhConfig::getInstance();        
       $this->file = $file;
    }
    
    public static function strip_html($data)
	{
		$dataLines = explode("\n",$data);
		$return = "";
		foreach ($dataLines as $line)
		{			
			if (($lineOutput = trim($line)) != ''){
				$return.= $lineOutput;	
				if (preg_match('/(\/\/|<!--)/',$lineOutput)) // In case comment is at the end somewhere, /gallery/publicupload/
					$return.= "\n";
			}
		}
		
		// Spaces have to be adjusted using CSS
		$return=str_replace("> <","><",$return);
		
		// Need space some templates
		$return=str_replace('<?php','<?php ',$return);
				
	    return $return;
	}
	

    /**
     * Open, parse, and return the template file.
     *
     * @param $file string the template file name
     */
    function fetch($fileTemplate = null) {
    	
    	$instance = erLhcoreClassSystem::instance();
    	
    	if(!$fileTemplate) { $fileTemplate = $this->file; }        
        if ($this->cacheEnabled == true && key_exists(md5($fileTemplate.$instance->WWWDirLang),$this->cacheTemplates))
        {
        	return $this->fetchExecute($this->cacheTemplates[md5($fileTemplate.$instance->WWWDirLang)]);
        }
        
        $cfg = erConfigClassLhConfig::getInstance();  
        $file = erLhcoreClassDesign::designtpl($fileTemplate);
          
        if ($this->templatecompile == true)
        {
	        $contentFile = php_strip_whitespace($file);  
	        
	        //Compile templates inclusions first level.             
	        $Matches = array();
			preg_match_all('/<\?php(.*?)include_once\(erLhcoreClassDesign::designtpl\(\'([a-zA-Z0-9-\.-\/\_]+)\'\)\)(.*?)\?\>/i',$contentFile,$Matches);       		
			foreach ($Matches[2] as $key => $Match)
			{	
				$contentFile = str_replace($Matches[0][$key],php_strip_whitespace(erLhcoreClassDesign::designtpl($Match)),$contentFile);	
			}		
			
			//Compile image css paths. Etc..
			$Matches = array();
			preg_match_all('/<\?=erLhcoreClassDesign::design\(\'([a-zA-Z0-9-\.-\/\_]+)\'\)(.*?)\?\>/i',$contentFile,$Matches); 
			foreach ($Matches[1] as $key => $Match)
			{	
				$contentFile = str_replace($Matches[0][$key],erLhcoreClassDesign::design($Match),$contentFile);	
			}
			
			//Compile translations
			$Matches = array();
			preg_match_all('/<\?=erTranslationClassLhTranslation::getInstance\(\)->getTranslation\(\'(.*?)\',\'(.*?)\'\)(.*?)\?\>/i',$contentFile,$Matches);
					
			foreach ($Matches[1] as $key => $TranslateContent)
			{	
				$contentFile = str_replace($Matches[0][$key],erTranslationClassLhTranslation::getInstance()->getTranslation($TranslateContent,$Matches[2][$key]),$contentFile);	
			}

			// Compile url addresses
			$Matches = array();
			preg_match_all('/<\?=erLhcoreClassDesign::baseurl\((.*?)\)(.*?)\?\>/i',$contentFile,$Matches); 
			foreach ($Matches[1] as $key => $UrlAddress)
			{	
				$contentFile = str_replace($Matches[0][$key],erLhcoreClassDesign::baseurl(trim($UrlAddress,'\'')),$contentFile);	
			}

			// Compile config settings
			$Matches = array();
			preg_match_all('/<\?=erConfigClassLhConfig::getInstance\(\)->conf->getSetting\((\s?)\'([a-zA-Z0-9-\.-\/\_]+)\'(\s?),(\s?)\'([a-zA-Z0-9-\.-\/\_]+)\'(\s?)\)(.*?)\?\>/i',$contentFile,$Matches); 
			foreach ($Matches[1] as $key => $UrlAddress)
			{	
				$contentFile = str_replace($Matches[0][$key],erConfigClassLhConfig::getInstance()->conf->getSetting($Matches[2][$key],$Matches[5][$key]),$contentFile);	
			}
				
			$sys = erLhcoreClassSystem::instance()->SiteDir;  
			$file = $sys . 'cache/compiledtemplates/'.md5($file.$instance->WWWDirLang).'.php';
			
			
				
			file_put_contents($file,erLhcoreClassTemplate::strip_html($contentFile));
				
	 	    $this->cacheTemplates[md5($fileTemplate.$instance->WWWDirLang)] = $file;		
			$this->storeCache();
        }
        
		return $this->fetchExecute($file);

    }
    
	function storeCache()
	{
		$this->cacheWriter->store('templateCache',$this->cacheTemplates);
	}  

	
	function fetchExecute($file)
	{
		@extract($this->vars,EXTR_REFS);          // Extract the vars to local namespace
        ob_start();                    // Start output buffering
        include($file);                // Include the file
        $contents = ob_get_contents(); // Get the contents of the buffer
        ob_end_clean();                // End buffering and discard
        return $contents; 
	}
	
	
}


?>