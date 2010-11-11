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
            $urlCfgDefault->addOrderedParameter( 'siteaccess' ); 
            $urlCfgDefault->addOrderedParameter( 'module' ); 
            $urlCfgDefault->addOrderedParameter( 'function' );
                       
            $urlInstance = new erLhcoreClassURL(erLhcoreClassSystem::instance()->RequestURI, $urlCfgDefault);
                
            $siteaccess = $urlInstance->getParam( 'siteaccess' );
            $cfgSite = erConfigClassLhConfig::getInstance(); 
                                                          
            $availableSiteaccess = $cfgSite->conf->getSetting( 'site', 'available_site_access' );
            $defaultSiteAccess = $cfgSite->conf->getSetting( 'site', 'default_site_access' );
                       
            if ($defaultSiteAccess != $siteaccess && in_array($siteaccess,$availableSiteaccess))
            {     
                $optionsSiteAccess = $cfgSite->conf->getSetting('site_access_options',$siteaccess);                      
                erLhcoreClassSystem::instance()->Language = $optionsSiteAccess['locale'];                         
                erLhcoreClassSystem::instance()->ThemeSite = $optionsSiteAccess['theme'];
                erLhcoreClassSystem::instance()->ContentLanguage = $optionsSiteAccess['content_language'];
                                         
                erLhcoreClassSystem::instance()->WWWDirLang = '/'.$siteaccess; 
                erLhcoreClassSystem::instance()->SiteAccess = $siteaccess; 
                
            } else {
                
                $optionsSiteAccess = $cfgSite->conf->getSetting('site_access_options',$defaultSiteAccess);
                
                // Falling back
                erLhcoreClassSystem::instance()->SiteAccess = $defaultSiteAccess; 
                erLhcoreClassSystem::instance()->Language = $optionsSiteAccess['locale'];                
                erLhcoreClassSystem::instance()->ThemeSite = $optionsSiteAccess['theme'];    
                erLhcoreClassSystem::instance()->ContentLanguage = $optionsSiteAccess['content_language'];
                
                // To reset possition counter
                $urlCfgDefault->removeOrderedParameter('siteaccess');
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