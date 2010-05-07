<?php

class erConfigClassLhConfig
{
    private static $instance = null;
    public $conf;
    
    public function __construct()
    {
        $sys = erLhcoreClassSystem::instance()->SiteDir;
        
        $ini = new ezcConfigurationArrayReader($sys . '/settings/settings.ini.php' );
        if ( $ini->configExists() )
        {
            $this->conf = $ini->load();
        } else {
           
        }
    }
    
    /**
     * This function should be used then value can be override by siteAccess
     * 
     * */
    public function getOverrideValue($section,$key)
    {
        $value = null;
        
        if ($this->conf->hasSetting($section,$key))
        $value = $this->conf->getSetting( $section, $key );
                
        $valueOverride = $this->conf->getSetting('site_access_options',erLhcoreClassSystem::instance()->SiteAccess);
        
        if (key_exists($key,$valueOverride))  
              return $valueOverride[$key];
              
        return $value;
    }
    
    public static function getInstance()  
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erConfigClassLhConfig();            
        }
        return self::$instance;
    }
    
    public function save()
    {
        $sys = erLhcoreClassSystem::instance()->SiteDir;    
            
        $writer = new ezcConfigurationArrayWriter($sys . 'settings/settings.ini.php');        
        $writer->setConfig( $this->conf );
        $writer->save();
    }
    
}


?>