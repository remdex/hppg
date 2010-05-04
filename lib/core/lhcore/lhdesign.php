<?php



class erLhcoreClassDesign
{
    public static function design($path)
    {
        $cfg = erConfigClassLhConfig::getInstance();
        $instance = erLhcoreClassSystem::instance();
                
        return $instance->wwwDir() . '/design/'. $cfg->conf->getSetting( 'site', 'theme' ) .'/' . $path;
    } 
    
    public static function designtpl($path)   
    {
        $cfg = erConfigClassLhConfig::getInstance();
        $instance = erLhcoreClassSystem::instance();         
        return $instance->SiteDir .'/design/' . $cfg->conf->getSetting( 'site', 'theme' ) .  '/tpl/'. $path;
    }
    
    public static function imagePath($path, $useCDN = false, $id = 0)   
    {   
        if ($useCDN == false ) {     
        	return '/albums/' . $path; 
        } else {                	
    		$cfg = erConfigClassLhConfig::getInstance();
    		$cdnServers = $cfg->conf->getSetting( 'cdn', 'images' );        		 
    		return $cdnServers[$id % count($cdnServers)] . '/albums/' . $path; 
        }
    }
    
    public static function baseurl($link = '')
    {
        $instance = erLhcoreClassSystem::instance();            
        return $instance->WWWDir . $instance->IndexFile .  $instance->WWWDirLang  . '/' . ltrim($link,'/');
    }
}


?>