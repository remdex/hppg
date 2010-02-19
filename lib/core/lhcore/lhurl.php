<?php


class erLhcoreClassURL extends ezcUrl {
    
    private static $instance = null;
    
    public function __construct($urlString, $urlCfgDefault)
    {
        parent::__construct($urlString, $urlCfgDefault);
    }
    
    public static function getInstance()  
    {
        if ( is_null( self::$instance ) )
        {            
            $sys = erLhcoreClassSystem::instance()->WWWDir;
            
            $urlCfgDefault = ezcUrlConfiguration::getInstance();
            $urlCfgDefault->basedir = $sys;
            $urlCfgDefault->script  = erLhcoreClassSystem::instance()->IndexFile;
            $urlCfgDefault->unorderedDelimiters = array( '(', ')' );            
            $urlCfgDefault->addOrderedParameter( 'language' ); 
            $urlCfgDefault->addOrderedParameter( 'module' ); 
            $urlCfgDefault->addOrderedParameter( 'function' );
                       
            $urlInstance = new erLhcoreClassURL(erLhcoreClassSystem::instance()->RequestURI, $urlCfgDefault);
                
            $language = $urlInstance->getParam( 'language' );
            $cfgSite = erConfigClassLhConfig::getInstance(); 
                                        
            if (strlen($language) == 2)
            {       
                $availableLocales = $cfgSite->conf->getSetting( 'site', 'available_locales' );            
                if (in_array($language.'_'.strtoupper($language),$availableLocales))
                {
                    erLhcoreClassSystem::instance()->Language = $language.'_'.strtoupper($language);
                    erLhcoreClassSystem::instance()->LanguageShortname = $language;                    
                    erLhcoreClassSystem::instance()->WWWDirLang = '/'.$language; 
                }
                
            } else {
                // Falling back
                erLhcoreClassSystem::instance()->Language = $cfgSite->conf->getSetting( 'site', 'locale' );                
                erLhcoreClassSystem::instance()->LanguageShortname = substr(erLhcoreClassSystem::instance()->Language,0,2);
                      
                // To reset possition counter
                $urlCfgDefault->removeOrderedParameter('language');
                $urlCfgDefault->removeOrderedParameter('module');
                $urlCfgDefault->removeOrderedParameter('function');
                
                // Reinit parameters
                $urlCfgDefault->addOrderedParameter( 'module' ); 
                $urlCfgDefault->addOrderedParameter( 'function' );
                
                //Apply default configuration             
                $urlInstance->applyConfiguration($urlCfgDefault);
              
            }
           
   
            
            self::$instance =  $urlInstance;        
        }
        return self::$instance;
    }
    
}
?>