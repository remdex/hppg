<?php


class erLhcoreClassCacheSystem implements ezcBaseConfigurationInitializer
{
     public static function configureObject( $id )
     {
         $options = array( 'ttl' => 3600 );
         $sys = erLhcoreClassSystem::instance()->SiteDir;  
         ezcCacheManager::createCache( $id , $sys . '/cache/cacheblock' , 'ezcCacheStorageFilePlain', $options ); 
     }  
     
     public static function getCache($section_cache,$cacheID)
     {
        $cache = ezcCacheManager::getCache( $section_cache ); 
         
        if (is_array($cacheID)) $cacheID = implode(',',$cacheID); 
        
        if ( ( $dataCache = $cache->restore( md5($cacheID) ) ) === false )
        { 
            return false;
        } else {
            return $dataCache;
        }
     }
     
     public static function startCacheBlock()
     {
         ob_start();
     }
     
     public static function endCacheBlock($section_cache,$cacheID)
     {
         $content = ob_get_clean();
         $cache = ezcCacheManager::getCache( $section_cache );
         if (is_array($cacheID)) $cacheID = implode(',',$cacheID);
                  
         $cache->store( md5($cacheID), $content );   
         return $content;
     }
     
     public static function compileClass($ModuleToRun,$ViewToRun)
     {
     	$cfg = erConfigClassLhConfig::getInstance();     	
     	$classFile = erLhcoreClassSystem::instance()->SiteDir . 'cache/compiledclasses/'.md5($ModuleToRun.$ViewToRun).'.php';
     	if ($cfg->conf->getSetting( 'site', 'classCompile' ) == true && !isset($GLOBALS['erCoreClassIncluded']) && !file_exists($classFile))
     	{    	
	     	$classes = array_merge(get_declared_classes(), get_declared_interfaces());     	
	     	$ret = array();
	
	     	foreach ($classes as $class)
	     	{
	     		if ((stristr($class,'ezc') || preg_match('/^er.*/',$class)) && !in_array($class,
	     		array('ezcConfigurationArrayReader',
	     		'ezcConfigurationFileReader',
	     		'erLhcoreClassURL',
	     		'erLhcoreClassCacheSystem',
	     		'erLhcoreClassLazyDatabaseConfiguration',
	     		'ezcBaseConfigurationInitializer',
	     		'ezcConfigurationReader',
	     		'ezcConfiguration',
	     		'ezcBaseInit',
	     		'erConfigClassLhConfig',
	     		'ezcUrlConfiguration',
	     		'ezcUrlTools',
	     		'ezcUrl',
	     		'ezcBase',
	     		'ezcBaseFeatures',
	     		'erLhcoreClassSystem')))
	     		{
	     			$refl  = new ReflectionClass($class);
	            	$file  = $refl->getFileName();
	            	if ($file != '')
	            	{            	
	            		$lines = file($file);
	            		
	            		$start = $refl->getStartLine() - 1;
	            		$end   = $refl->getEndLine();
	            		
	            		$ret = array_merge($ret, array_slice($lines, $start, ($end - $start)));            		
	            	} 	                     	
	     		}
	     		
	     	}
			file_put_contents($classFile,'<?php '.implode('',$ret));
     	}     	 	
     }
  
     public static function includeCompiledClasses($ModuleToRun,$ViewToRun)
     {     	
     	$cfg = erConfigClassLhConfig::getInstance(); 
     	$classFile = erLhcoreClassSystem::instance()->SiteDir . 'cache/compiledclasses/'.md5($ModuleToRun.$ViewToRun).'.php';
     	
     	if ($cfg->conf->getSetting( 'site', 'classCompile' ) == true && file_exists($classFile))
     	{   
     		include_once($classFile);
     		$GLOBALS['erCoreClassIncluded'] = true;
     	}
     }
     
}

class CSCacheAPC {

    static private $m_objMem = NULL;
    public $cacheEngine = null;
    public $cacheGlobalKey = null;

    public $cacheKeys = array(
    'last_hits_version',        // Last visited pages
    'most_popular_version',     // Most popular images, watched times
    'top_rated',                // Top rated images
    'last_uploads',             // Last uploaded images
    'last_commented',           // Last commented images
    'site_version',             // Global site version
    'album_count_version',      // Album count version
    'sphinx_cache_version',     // Sphinx search cache version
    );
    
    public function increaseImageManipulationCache()
    {
        $this->increaseCacheVersion('last_hits_version');
        $this->increaseCacheVersion('most_popular_version');
        $this->increaseCacheVersion('last_uploads');
        $this->increaseCacheVersion('top_rated');
        $this->increaseCacheVersion('last_commented');        
        $this->increaseCacheVersion('sphinx_cache_version');        
        $this->delete(md5('index_page'));
    }
    
    function __construct() {  
              
        $cacheEngineClassName = erConfigClassLhConfig::getInstance()->conf->getSetting( 'cacheEngine', 'className' );        
        $this->cacheGlobalKey = erConfigClassLhConfig::getInstance()->conf->getSetting( 'cacheEngine', 'cache_global_key' );
                    
        if ($cacheEngineClassName !== false)
        {
            $this->cacheEngine = new $cacheEngineClassName();
        }
    }
         
    function __destruct() {
        
    }
    
    static function getMem() {
        if (self::$m_objMem == NULL) {
            self::$m_objMem = new CSCacheAPC();
        }
        return self::$m_objMem;
    }

    function delete($key) {
        if (isset($GLOBALS[$key])) unset($GLOBALS[$key]);
        
        if ( $this->cacheEngine != null )
        {
            $this->cacheEngine->set($this->cacheGlobalKey.$key,false,0);
        }
    }

    function restore($key) {
        
        if (isset($GLOBALS[$key]) && $GLOBALS[$key] !== false) return $GLOBALS[$key];

        if ( $this->cacheEngine != null )
        {       
            $GLOBALS[$key] = $this->cacheEngine->get($this->cacheGlobalKey.$key);
        } else {
            $GLOBALS[$key] = false;
        }
               
        return $GLOBALS[$key];
    }

    function getCacheVersion($cacheVariable, $valuedefault = 1, $ttl = 0)
    {
        if (isset($GLOBALS['CacheKeyVersion_'.$cacheVariable])) return $GLOBALS['CacheKeyVersion_'.$cacheVariable];
        
        if ( $this->cacheEngine != null )
        {
            if (($version = $this->cacheEngine->get($this->cacheGlobalKey.$cacheVariable)) == false){
                $version = $valuedefault;
                $this->cacheEngine->set($this->cacheGlobalKey.$cacheVariable,$version,0,$ttl);
                $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $valuedefault;
            } else $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $version;
            
        } else {
            $version = $valuedefault;
            $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $valuedefault;
        }
        
        return $version;        
    }
    
    function increaseCacheVersion($cacheVariable)
    {
        if ( $this->cacheEngine != null )
        {            
            if (($version = $this->get($this->cacheGlobalKey.$cacheVariable)) == false){
                 $this->set($this->cacheGlobalKey.$cacheVariable,1);
                 $GLOBALS['CacheKeyVersion_'.$cacheVariable] = 1;
            } else {$this->increment($this->cacheGlobalKey.$cacheVariable);$GLOBALS['CacheKeyVersion_'.$cacheVariable] = $version+1;}
            
        } else {
            $GLOBALS['CacheKeyVersion_'.$cacheVariable] = 1;
        }        
    }
    
    function store($key, $value, $ttl = 36000) {        
        if ( $this->cacheEngine != null )
        {
            $GLOBALS[$key] = $value;
            $this->cacheEngine->set($this->cacheGlobalKey.$key,$value,0,$ttl);
        } else {
           $GLOBALS[$key] = $value; 
        }
    }      
}




class erLhcoreClassSystem{
    
	
    
    static function instance()
    {
        if ( empty( $GLOBALS['LhSysInstance'] ) )
        {
            $GLOBALS['LhSysInstance'] = new erLhcoreClassSystem();
        }
        return $GLOBALS['LhSysInstance'];
    }
    
    
    static function init()
    {
    	$index = "index.php";
		$def_index = '';
       
        $instance = erLhcoreClassSystem::instance();
       
 		$isCGI = ( substr( php_sapi_name(), 0, 3 ) == 'cgi' );
        $force_VirtualHost = false;        

        $phpSelf = $_SERVER['PHP_SELF'];

        // Find out, where our files are.
        if ( preg_match( "!(.*/)$index$!", $_SERVER['SCRIPT_FILENAME'], $regs ) )
        {
            $siteDir = $regs[1];
            $index = "/$index";
        }
        elseif ( preg_match( "!(.*/)$index/?!", $phpSelf, $regs ) )
        {
            // Some people using CGI have their $_SERVER['SCRIPT_FILENAME'] not right... so we are trying this.
            $siteDir = $_SERVER['DOCUMENT_ROOT'] . $regs[1];
            $index = "/$index";
        }
        else
        {
            // Fallback... doesn't work with virtual-hosts, but better than nothing
            $siteDir = "./";
            $index = "/$index";
        }
        if ( $isCGI and !$force_VirtualHost )
        {
            $index .= '?';
        }

        // Setting the right include_path
        $includePath = ini_get( "include_path" );
        if ( trim( $includePath ) != "" )
        {
            $includePath = $includePath . /*$instance->envSeparator()*/'/'.  $siteDir;
        }
        else
        {
            $includePath = $siteDir;
        }
        ini_set( "include_path", $includePath );

        $scriptName = $_SERVER['SCRIPT_NAME'];
        // Get the webdir.

        $wwwDir = "";

        if ( $force_VirtualHost )
        {
            $wwwDir = "";
        }
        else
        {
            if ( preg_match( "!(.*)$index$!", $scriptName, $regs ) )
                $wwwDir = $regs[1];
            if ( preg_match( "!(.*)$index$!", $phpSelf, $regs ) )
                $wwwDir = $regs[1];
        }

        if ( ! $isCGI || $force_VirtualHost )
        {
            $requestURI = $_SERVER['REQUEST_URI'];
        }
        else
        {
            $requestURI = $_SERVER['QUERY_STRING'];

            /* take out PHPSESSID, if url-encoded */
            if ( preg_match( "/(.*)&PHPSESSID=[^&]+(.*)/", $requestURI, $matches ) )
            {
                $requestURI = $matches[1].$matches[2];
            }
        }

        // Fallback... Finding the paths above failed, so $_SERVER['PHP_SELF'] is not set right.
        if ( $siteDir == "./" )
            $phpSelf = $requestURI;

        if ( ! $isCGI )
        {
            $index_reg = str_replace( ".", "\\.", $index );
            // Trick: Rewrite setup doesn't have index.php in $_SERVER['PHP_SELF'], so we don't want an $index
            if ( !preg_match( "!.*$index_reg.*!", $phpSelf ) || $force_VirtualHost )
            {
                $index = "";
            }
            else
            {                
                // Get the right $_SERVER['REQUEST_URI'], when using nVH setup.
                if ( preg_match( "!^$wwwDir$index(.*)!", $phpSelf, $req ) )
                {
                    if ( !$req[1] )
                    {
                        if ( $phpSelf != "$wwwDir$index" and preg_match( "!^$wwwDir(.*)!", $requestURI, $req ) )
                        {
                            $requestURI = $req[1];
                            $index = '';
                        }
                        elseif ( $phpSelf == "$wwwDir$index" and
                               ( preg_match( "!^$wwwDir$index(.*)!", $requestURI, $req ) or preg_match( "!^$wwwDir(.*)!", $requestURI, $req ) ) )
                        {
                            $requestURI = $req[1];
                        }
                    }
                    else
                    {
                        $requestURI = $req[1];
                    }
                }
            }
        }
        if ( $isCGI and $force_VirtualHost )
            $index = '';
        // Remove url parameters
        if ( $isCGI and !$force_VirtualHost )
        {
            $pattern = "!(\/[^&]+)!";
        }
        else
        {
            $pattern = "!([^?]+)!";
        }
        if ( preg_match( $pattern, $requestURI, $regs ) )
        {
            $requestURI = $regs[1];
        }

        // Remove internal links
        if ( preg_match( "!([^#]+)!", $requestURI, $regs ) )
        {
            $requestURI = $regs[1];
        }

        if ( !$isCGI )
        {
            $currentPath = substr( $_SERVER['SCRIPT_FILENAME'] , 0, -strlen( 'index.php' ) );
            if ( strpos( $currentPath, $_SERVER['DOCUMENT_ROOT']  ) === 0 )
            {
                $prependRequest = substr( $currentPath, strlen( $_SERVER['DOCUMENT_ROOT'] ) );

                if ( strpos( $requestURI, $prependRequest ) === 0 )
                {
                    $requestURI = substr( $requestURI, strlen( $prependRequest ) - 1 );
                    $wwwDir = substr( $prependRequest, 0, -1 );
                }
            }
        }

    
        $instance->SiteDir = $siteDir;
        $instance->WWWDir = $wwwDir;
        $instance->WWWDirLang = '';
        $instance->IndexFile = $index;
        $instance->RequestURI = $requestURI;        
        
    }

    function wwwDir()
    {
        return $this->WWWDir;
    }
    
    /// The path to where all the code resides
    public $SiteDir;
    /// The access path of the current site view
    /// The relative directory path of the vhless setup
    public $WWWDir;
    
    // The www dir used in links formating
    public $WWWDirLang;
    
    /// The filepath for the index
    public $IndexFile;
    /// The uri which is used for parsing module/view information from, may differ from $_SERVER['REQUEST_URI']
    public $RequestURI;
    /// The type of filesystem, is either win32 or unix. This often used to determine os specific paths.
    
    /// Current language
    public $Language;
    public $LanguageShortname;

}


?>