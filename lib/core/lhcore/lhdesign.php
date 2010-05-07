<?php



class erLhcoreClassDesign
{
    public static function design($path)
    {
    	$debug = ezcDebug::getInstance(); 
    	
    	$logString = '';
    	    	
        $instance = erLhcoreClassSystem::instance();  
        foreach ($instance->ThemeSite as $designDirectory)
        {
            $fileDir = $instance->SiteDir . '/design/'. $designDirectory .'/' . $path; 
                       
            if (file_exists($fileDir)) {  
            	
            	$logString .= "Found IN - ".$fileDir."<br/>";          	
            	$debug->log( $logString, 0, array( "source"  => "erLhcoreClassDesign", "category" =>  "design - $path" )  );
            	
            	return $instance->wwwDir() . '/design/'. $designDirectory .'/' . $path;
            }
            else 
            $logString .= "Not found IN - ".$fileDir."<br/>";
        } 
        
        $debug->log( $logString, 0, array( "source"  => "shop", "erLhcoreClassDesign" =>  "design - $path" )  );
    } 
    
    public static function designtpl($path)   
    {
    	$logString = '';
    	
    	$debug = ezcDebug::getInstance();
    	
        $instance = erLhcoreClassSystem::instance();  
        foreach ($instance->ThemeSite as $designDirectory)
        {
            $tplDir = $instance->SiteDir .'/design/' . $designDirectory .  '/tpl/'. $path;
            
            if (file_exists($tplDir)) {
            	
            	$logString .= "Found IN - ".$tplDir."<br/>";          	
            	$debug->log( $logString, 0, array( "source"  => "erLhcoreClassDesign", "category" =>  "designtpl - $path" )  );
            	return $tplDir;
            } else {
            	$logString .= "Not found IN - ".$tplDir."<br/>";
            }
        }     
        
        $debug->log( $logString, 0, array( "source"  => "shop", "erLhcoreClassDesign" =>  "designtpl - $path" )  );
          
        return ;
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