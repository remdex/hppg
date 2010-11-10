<?php



class erLhcoreClassDesign
{
    public static function design($path)
    {
    	
    	$debugOutput = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'debug_output' );
    	
    	if ($debugOutput == true) {
    		$logString = '';
    		$debug = ezcDebug::getInstance(); 
    	}
    	   	
        $instance = erLhcoreClassSystem::instance();  
        foreach ($instance->ThemeSite as $designDirectory)
        {
            $fileDir = $instance->SiteDir . '/design/'. $designDirectory .'/' . $path; 
                       
            if (file_exists($fileDir)) {  
            	
            	if ($debugOutput == true) {
            		$logString .= "Found IN - ".$fileDir."<br/>";          	
            		$debug->log( $logString, 0, array( "source"  => "erLhcoreClassDesign", "category" =>  "design - $path" )  );
            	}
            	
            	return $instance->wwwDir() . '/design/'. $designDirectory .'/' . $path;
            } else { 
            	if ($debugOutput == true)
	            $logString .= "Not found IN - ".$fileDir."<br/>";
            }
        } 
        
        if ($debugOutput == true)
        $debug->log( $logString, 0, array( "source"  => "shop", "erLhcoreClassDesign" =>  "design - $path" )  );
       
    } 
    
    public static function designtpl($path)   
    {
    	$debugOutput = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'debug_output' );
    	
    	if ($debugOutput == true) {
    		$logString = '';    	
    		$debug = ezcDebug::getInstance();
    	}
    	
        $instance = erLhcoreClassSystem::instance();  
        foreach ($instance->ThemeSite as $designDirectory)
        {
            $tplDir = $instance->SiteDir .'/design/' . $designDirectory .  '/tpl/'. $path;
            
            if (file_exists($tplDir)) {
            	if ($debugOutput == true) {
            		$logString .= "Found IN - ".$tplDir."<br/>";          	
            		$debug->log( $logString, 0, array( "source"  => "erLhcoreClassDesign", "category" =>  "designtpl - $path" )  );
            	}
            	return $tplDir;
            } else {
            	if ($debugOutput == true)
            	$logString .= "Not found IN - ".$tplDir."<br/>";
            }
        }   
          
        if ($debugOutput == true)
        $debug->log( $logString, 0, array( "source"  => "shop", "erLhcoreClassDesign" =>  "designtpl - $path" )  );
          
        return ;
    }
    
    public static function imagePath($path, $useCDN = false, $id = 0)   
    {             
        $instance = erLhcoreClassSystem::instance();
        if ($useCDN == false ) {    
        	return $instance->wwwDir() . '/albums/' . $path; 
        } else {                	
    		$cfg = erConfigClassLhConfig::getInstance();
    		$cdnServers = $cfg->conf->getSetting( 'cdn', 'images' );        		 
    		return $cdnServers[$id % count($cdnServers)] . $instance->wwwDir() . '/albums/' . $path; 
        }
    }
    
    public static function baseurl($link = '')
    {
        $instance = erLhcoreClassSystem::instance();            
        return $instance->WWWDir . $instance->IndexFile .  $instance->WWWDirLang  . '/' . ltrim($link,'/');
    }
    
    public static function baseurldirect($link = '')
    {
        $instance = erLhcoreClassSystem::instance();            
        return $instance->WWWDir . $instance->IndexFile . '/' . ltrim($link,'/');
    }
    
    public static function designCSS($files)
    {
        $debugOutput = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'debug_output' );
    	$items = explode(';',$files);
        
    	if ($debugOutput == true) {
    		$logString = '';
    		$debug = ezcDebug::getInstance(); 
    	}
    	    	
    	$filesToCompress = '';
    	foreach ($items as $path)
    	{	
            $instance = erLhcoreClassSystem::instance();  
            foreach ($instance->ThemeSite as $designDirectory)
            {
                $fileDir = $instance->SiteDir . 'design/'. $designDirectory .'/' . $path; 
                           
                
                if (file_exists($fileDir)) {  
                	
                    $fileContent = file_get_contents($fileDir);
                                        
                    if ( preg_match_all("/url\(\s*[\'|\"]?([A-Za-z0-9_\-\/\.\\%?&#]+)[\'|\"]?\s*\)/ix", $fileContent, $urlMatches) )
                    {
                       $urlMatches = array_unique( $urlMatches[1] );
                       $cssPathArray   = explode( '/', '/design/'. $designDirectory .'/' . $path );
                       // Pop the css file name
                       array_pop( $cssPathArray );
                       $cssPathCount = count( $cssPathArray );
                       foreach( $urlMatches as $match )
                       {
                           $match = str_replace( '\\', '/', $match );
                           $relativeCount = substr_count( $match, '../' );
                           // Replace path if it is realtive
                           if ( $match[0] !== '/' and strpos( $match, 'http:' ) === false )
                           {
                               $cssPathSlice = $relativeCount === 0 ? $cssPathArray : array_slice( $cssPathArray  , 0, $cssPathCount - $relativeCount  );
                               $newMatchPath = $instance->wwwDir() . implode('/', $cssPathSlice) . '/' . str_replace('../', '', $match);
                               $fileContent = str_replace( $match, $newMatchPath, $fileContent );
                           }
                       }
                    }
                                     
                    $filesToCompress .= $fileContent;
                	
                } else { 
                	if ($debugOutput == true)
    	            $logString .= "Not found IN - ".$fileDir."<br/>";
                }
            } 
    	}

    	   	
        $sys = erLhcoreClassSystem::instance()->SiteDir; 
        $filesToCompress = self::optimizeCSS($filesToCompress,3);
        $fileName = md5($filesToCompress.$instance->WWWDirLang);
        $file = $sys . 'cache/compiledtemplates/'.$fileName.'.css'; 
        
        if (!file_exists($file)) {    		   
            file_put_contents($file,$filesToCompress);
        }
        
        return $instance->wwwDir() . '/cache/compiledtemplates/'.$fileName.'.css'; 
    }
    
    
    /**
     * 'compress' css code by removing whitespace
     *
     * @param string $css Concated Css string
     * @param int $packLevel Level of packing, values: 2-3
     * @return string
     */
    static function optimizeCSS( $css, $packLevel )
    {
        // normalize line feeds
        $css = str_replace(array("\r\n", "\r"), "\n", $css);

        // remove multiline comments
        $css = preg_replace('!(?:\n|\s|^)/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = preg_replace('!(?:;)/\*[^*]*\*+([^/][^*]*\*+)*/!', ';', $css);

        // remove whitespace from start and end of line + multiple linefeeds
        $css = preg_replace(array('/\n\s+/', '/\s+\n/', '/\n+/'), "\n", $css);

        if ( $packLevel > 2 )
        {
            // remove space around ':' and ','
            $css = preg_replace(array('/:\s+/', '/\s+:/'), ':', $css);
            $css = preg_replace(array('/,\s+/', '/\s+,/'), ',', $css);

            // remove unnecesery line breaks
            $css = str_replace(array(";\n", '; '), ';', $css);
            $css = str_replace(array("}\n","\n}", ';}'), '}', $css);
            $css = str_replace(array("{\n", "\n{", '{;'), '{', $css);

            // optimize css
            $css = str_replace(array(' 0em', ' 0px',' 0pt', ' 0pc'), ' 0', $css);
            $css = str_replace(array(':0em', ':0px',':0pt', ':0pc'), ':0', $css);
            $css = str_replace(' 0 0 0 0;', ' 0;', $css);
            $css = str_replace(':0 0 0 0;', ':0;', $css);

            // these should use regex to work on all colors
            $css = str_replace(array('#ffffff','#FFFFFF'), '#fff', $css);
            $css = str_replace('#000000', '#000', $css);
        }
        return $css;
    }
}


?>