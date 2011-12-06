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
            
            $sysConfiguration = erLhcoreClassSystem::instance();
            
            $urlCfgDefault = ezcUrlConfiguration::getInstance();
            $urlCfgDefault->basedir = $sysConfiguration->WWWDir;
            $urlCfgDefault->script  = $sysConfiguration->IndexFile;
            $urlCfgDefault->unorderedDelimiters = array( '(', ')' );            
            $urlCfgDefault->addOrderedParameter( 'siteaccess' ); 
            $urlCfgDefault->addOrderedParameter( 'module' ); 
            $urlCfgDefault->addOrderedParameter( 'function' );
                       
            $urlInstance = new erLhcoreClassURL($sysConfiguration->RequestURI, $urlCfgDefault);
                
            $siteaccess = $urlInstance->getParam( 'siteaccess' );
            $cfgSite = erConfigClassLhConfig::getInstance(); 
                                                          
            $availableSiteaccess = $cfgSite->getSetting( 'site', 'available_site_access' );
            $defaultSiteAccess = $cfgSite->getSetting( 'site', 'default_site_access' );
                       
            if ($defaultSiteAccess != $siteaccess && in_array($siteaccess,$availableSiteaccess))
            {     
                $optionsSiteAccess = $cfgSite->getSetting('site_access_options',$siteaccess);                      
                $sysConfiguration->Language = $optionsSiteAccess['locale'];                         
                $sysConfiguration->ThemeSite = $optionsSiteAccess['theme'];
                $sysConfiguration->ContentLanguage = $optionsSiteAccess['content_language'];
                                         
                $sysConfiguration->WWWDirLang = '/'.$siteaccess; 
                $sysConfiguration->SiteAccess = $siteaccess; 
                
                if ($optionsSiteAccess['locale'] != 'en_EN')
                {
                    $params = erLhcoreClassDesign::translateToOriginal($urlInstance->getParam( 'module' ), $urlInstance->getParam( 'function' ));
                    $urlInstance->setParam('module',$params['module']);
                    $urlInstance->setParam('function',$params['function']);
                }
                
            } else {
                
                $optionsSiteAccess = $cfgSite->getSetting('site_access_options',$defaultSiteAccess);
                
                // Falling back
                $sysConfiguration->SiteAccess = $defaultSiteAccess; 
                $sysConfiguration->Language = $optionsSiteAccess['locale'];                
                $sysConfiguration->ThemeSite = $optionsSiteAccess['theme'];    
                $sysConfiguration->ContentLanguage = $optionsSiteAccess['content_language'];
                
                // To reset possition counter
                $urlCfgDefault->removeOrderedParameter('siteaccess');
                $urlCfgDefault->removeOrderedParameter('module');
                $urlCfgDefault->removeOrderedParameter('function');
                         
                // Reinit parameters
                $urlCfgDefault->addOrderedParameter( 'module' ); 
                $urlCfgDefault->addOrderedParameter( 'function' );
                
                //Apply default configuration             
                $urlInstance->applyConfiguration($urlCfgDefault);
                
                if ($optionsSiteAccess['locale'] != 'en_EN')
                {
                    $params = erLhcoreClassDesign::translateToOriginal($urlInstance->getParam( 'module' ), $urlInstance->getParam( 'function' ));
                    $urlInstance->setParam('module',$params['module']);
                    $urlInstance->setParam('function',$params['function']);
                } 
            }
           
   
            
            self::$instance =  $urlInstance;        
        }
        return self::$instance;
    }
    
}
?>