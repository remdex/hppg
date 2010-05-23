<?php

class erConfigClassLhCacheConfig
{
    private static $instance = null;
    public $conf;
    
    private $expireOptions = array('translationfile','accessfile');
    private $sessionExpireOptions = array('access_array','lhCacheUserDepartaments');
    private $sitedir = '';
    
    public function __construct()
    {
        $sys = erLhcoreClassSystem::instance()->SiteDir;
        $this->sitedir = $sys;
        
        $ini = new ezcConfigurationArrayReader($this->sitedir . '/cache/cacheconfig/settings.ini.php' );
        if ( $ini->configExists() )
        {
            $this->conf = $ini->load();
        } else {
           
        }
    }
    
    public static function getInstance()  
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erConfigClassLhCacheConfig();            
        }
        return self::$instance;
    }
    
    public function save()
    {              
        $writer = new ezcConfigurationArrayWriter($this->sitedir . 'cache/cacheconfig/settings.ini.php');        
        $writer->setConfig( $this->conf );
        $writer->save();
    }
    
    public function expireCache()
    {
        foreach ($this->expireOptions as $option)
        {
            $this->conf->setSetting( 'cachetimestamps', $option, 0);
        }  
        
        foreach ($this->sessionExpireOptions as $option)
        {
            if (isset($_SESSION[$option])) unset($_SESSION[$option]);
        }
        
		if (file_exists($this->sitedir. 'cache/cacheconfig/templateCache-.cache'))
		{
			unlink($this->sitedir. 'cache/cacheconfig/templateCache-.cache');
		}
      
		$compiledClasses = ezcBaseFile::findRecursive( 'cache/compiledclasses',array( '@.php@' ) );
		
		foreach ($compiledClasses as $compiledClass)
		{
			unlink($compiledClass);
		}
		
		$compiledTemplates = ezcBaseFile::findRecursive( 'cache/compiledtemplates',array( '@.php@' ) );
		
		foreach ($compiledTemplates as $compiledTemplate)
		{
			unlink($compiledTemplate);
		}		
		
		$instance = CSCacheAPC::getMem(); 
		$instance->increaseImageManipulationCache();
		
        $this->save();       
    }
    
    
}


?>