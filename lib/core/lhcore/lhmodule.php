<?php

class erLhcoreClassModule{
    
    static function runModule()
    {
        if (isset(self::$currentModule[self::$currentView]))
        {            
            // Just to start session
            $currentUser = erLhcoreClassUser::instance();
                
            $Params = array();
            $Params['module'] = self::$currentModule[self::$currentView];
            $Params['module']['name'] = self::$currentModule;
            $Params['module']['view'] = self::$currentView;
            
            $urlCfgDefault = ezcUrlConfiguration::getInstance();
            $url = erLhcoreClassURL::getInstance();            
            $urlCfgDefault->addUnorderedParameter( 'page' );                                       
            $url->applyConfiguration( $urlCfgDefault );
            $Params['user_parameters_unordered']['page'] = $url->getParam('page');      
             
            if (isset(self::$currentModule[self::$currentView]['params']))
            {          
                foreach (self::$currentModule[self::$currentView]['params'] as $userParameter)
                {           
                   $urlCfgDefault->addOrderedParameter( $userParameter );                                       
                   $url->applyConfiguration( $urlCfgDefault );
                   $Params['user_parameters'][$userParameter] =  $url->getParam($userParameter); 
                }               
            }
            
            if (isset(self::$currentModule[self::$currentView]['uparams']))
            {                         
                foreach (self::$currentModule[self::$currentView]['uparams'] as $userParameter)
                {           
                   $urlCfgDefault->addUnorderedParameter( $userParameter );                                       
                   $url->applyConfiguration( $urlCfgDefault );

                   $Params['user_parameters_unordered'][$userParameter] =  $url->getParam($userParameter); 
                }               
            }
            
            // Function set, check permission
            if (isset($Params['module']['functions']))
            {                                 
                if (!$currentUser->hasAccessTo('lh'.self::$currentModuleName,$Params['module']['functions']))
                {
                   erLhcoreClassModule::redirect('user/login');
                   exit;
                }
            }
                      
            if(isset($Params['module']['limitations']))
            {       
                $access = call_user_func($Params['module']['limitations']['self']['method'],$Params['user_parameters'][$Params['module']['limitations']['self']['param']],$currentUser->hasAccessTo('lh'.self::$currentModuleName,$Params['module']['limitations']['global']));               	
                
                if ($access == false) {                
                	include_once('modules/lhkernel/nopermissionobject.php');  
                   	return $Result;
                } else {
                	$Params['user_object'] = $access;
                }                                
            }
                        
            include_once(erLhcoreClassModule::getModuleFile(self::$currentModuleName,self::$currentView)); 
                        
            if (isset($Params['module']['pagelayout']) && !isset($Result['pagelayout'])) {
                $Result['pagelayout'] = $Params['module']['pagelayout'];
            }
               
            return $Result;
        } else {
            erLhcoreClassModule::redirect();
            exit;
        }
    }
    
    
    public static function getModuleFile() {
        
        $cfg = erConfigClassLhConfig::getInstance();
        $cacheEnabled = $cfg->conf->getSetting( 'site', 'modulecompile' );
        
        if ($cacheEnabled === false) {
            return self::$currentModule[self::$currentView]['script_path'];
        } else {
                                                     
            $instance = erLhcoreClassSystem::instance();
            $cacheKey = md5(self::$currentModuleName.'_'.self::$currentView.'_'.$instance->WWWDirLang);
            
            if ( ($cacheModules = self::$cacheInstance->restore('moduleCache_'.self::$currentModuleName.'_version_'.self::$cacheVersionSite)) !== false && key_exists($cacheKey,$cacheModules))
            {
            	return $cacheModules[$cacheKey];
            }
                        
            $cacheWriter = new ezcCacheStorageFileArray(erLhcoreClassSystem::instance()->SiteDir . 'cache/cacheconfig/');            
            if (($cacheModules = $cacheWriter->restore('moduleCache_'.self::$currentModuleName)) == false)
            {        	
            	$cacheWriter->store('moduleCache_'.self::$currentModuleName,array());
            	$cacheModules = array();
            }               
           
            if (key_exists($cacheKey,$cacheModules))
            {         
                    self::$cacheInstance->store('moduleCache_'.self::$currentModuleName.'_version_'.self::$cacheVersionSite,$cacheModules);                    
            		return $cacheModules[$cacheKey];            	
            }
            
            $file = self::$currentModule[self::$currentView]['script_path'];          
            $contentFile = php_strip_whitespace($file);

                       
            $fileCompiled = $instance->SiteDir . 'cache/compiledtemplates/'.md5($file.$instance->WWWDirLang).'.php';
            
            $Matches = array();
			preg_match_all('/erTranslationClassLhTranslation::getInstance\(\)->getTranslation\(\'(.*?)\',\'(.*?)\'\)/i',$contentFile,$Matches);					
			foreach ($Matches[1] as $key => $TranslateContent)
			{	
				$contentFile = str_replace($Matches[0][$key],'\''.erTranslationClassLhTranslation::getInstance()->getTranslation($TranslateContent,$Matches[2][$key]).'\'',$contentFile);	
			}
              
			$Matches = array();
			preg_match_all('/erLhcoreClassDesign::baseurl\((.*?)\)/i',$contentFile,$Matches); 
			foreach ($Matches[1] as $key => $UrlAddress)
			{		
				$contentFile = str_replace($Matches[0][$key],'\''.erLhcoreClassDesign::baseurl(trim($UrlAddress,'\'')).'\'',$contentFile);	
			}         	

			$contentFile = str_replace('erLhcoreClassSystem::instance()->SiteAccess','\''.erLhcoreClassSystem::instance()->SiteAccess.'\'',$contentFile);
								
			$Matches = array();
			preg_match_all('/erConfigClassLhConfig::getInstance\(\)->conf->getSetting\((\s?)\'([a-zA-Z0-9-\.-\/\_]+)\'(\s?),(\s?)\'([a-zA-Z0-9-\.-\/\_]+)\'(\s?)\)/i',$contentFile,$Matches); 
			foreach ($Matches[1] as $key => $UrlAddress)
			{	
			    $valueConfig = erConfigClassLhConfig::getInstance()->conf->getSetting($Matches[2][$key],$Matches[5][$key]);
			    $valueReplace = '';
			    
			    if (is_bool($valueConfig)){
			        $valueReplace = $valueConfig == false ? 'false' : 'true';
			    } elseif (is_integer($valueConfig)) {
			        $valueReplace = $valueConfig;
			    } elseif (is_array($valueConfig)) {
			        $valueReplace = var_export($valueConfig,true);
			    } else {
			        $valueReplace = '\''.$valueConfig.'\'';
			    }
			    
				$contentFile = str_replace($Matches[0][$key],$valueReplace,$contentFile);	
			}
			
			file_put_contents($fileCompiled,$contentFile);
            
			$cacheModules[$cacheKey] = $fileCompiled;		
					
			$cacheWriter->store('moduleCache_'.self::$currentModuleName,$cacheModules);
			self::$cacheInstance->store('moduleCache_'.self::$currentModuleName.'_version_'.self::$cacheVersionSite,$cacheModules);
					
            return $fileCompiled;
        }
        
    }
   
    
    public static function getModule($module){
           
        $cfg = erConfigClassLhConfig::getInstance();               
        self::$moduleCacheEnabled = $cfg->conf->getSetting( 'site', 'modulecompile' );

        if (self::$moduleCacheEnabled === true) { 
            if ( ($cacheModules = self::$cacheInstance->restore('moduleFunctionsCache_'.$module.'_version_'.self::$cacheVersionSite)) !== false)
            {     
            	return $cacheModules;
            }
           
            $cacheWriter = new ezcCacheStorageFileArray(erLhcoreClassSystem::instance()->SiteDir . 'cache/cacheconfig/');
            if ( ($cacheModules = $cacheWriter->restore('moduleFunctionsCache_'.$module)) == false)
            {        	
            	$cacheWriter->store('moduleFunctionsCache_'.$module,array());
            	$cacheModules = array();
            }
                            
            if (count($cacheModules) > 0){
                self::$cacheInstance->store('moduleFunctionsCache_'.$module.'_version_'.self::$cacheVersionSite,$cacheModules);
                return $cacheModules;
            }
        }  
         
        $extensions = $cfg->conf->getSetting('site','extensions');
        
        $ViewListCompiled = array();
        
        // Is it core module
        if (file_exists('modules/lh'.$module.'/module.php'))
        {
            include_once('modules/lh'.$module.'/module.php');
            
            foreach ($ViewList as $view => $params){
                $ViewList[$view]['script_path'] = 'modules/lh'.$module.'/'.$view.'.php';
            }
                       
            $ViewListCompiled = array_merge($ViewListCompiled,$ViewList);
        }
        
        // Is it extension module        
        foreach ($extensions as $extension)
        {   
            if (file_exists('extension/'.$extension.'/modules/lh'.$module.'/module.php')){
              
                include_once('extension/'.$extension.'/modules/lh'.$module.'/module.php');
                
                foreach ($ViewList as $view => $params){
                    $ViewList[$view]['script_path'] = 'extension/'.$extension.'/modules/lh'.$module.'/'.$view.'.php';
                }                          
                
                $ViewListCompiled = array_merge($ViewListCompiled,$ViewList);
             }
        }
                       
        if (count($ViewListCompiled) > 0) {
            if (self::$moduleCacheEnabled === true) { 
                $cacheWriter->store('moduleFunctionsCache_'.$module,$ViewListCompiled);
                self::$cacheInstance->store('moduleFunctionsCache_'.$module.'_version_'.self::$cacheVersionSite,$ViewListCompiled);
            }            
            return $ViewListCompiled;
        }
               
        
        // Module does not exists
        return false;
        
    }
    
    public static function moduleInit()
    {                 
                       
        $url = erLhcoreClassURL::getInstance();
        $cfg = erConfigClassLhConfig::getInstance();
        
        self::$currentModuleName = $url->getParam( 'module' );
        self::$currentView = $url->getParam( 'function' );
        self::$cacheInstance = CSCacheAPC::getMem();
        self::$cacheVersionSite = self::$cacheInstance->getCacheVersion('site_version');
        
        if (!is_null($url->getParam( 'module' )) && (self::$currentModule = erLhcoreClassModule::getModule(self::$currentModuleName)) !== false) {  
            
        } else {	
        	
            $params = $cfg->getOverrideValue('site','default_url');
            self::$currentView = $params['view'];
            self::$currentModuleName = $params['module'];
            self::$currentModule = erLhcoreClassModule::getModule(self::$currentModuleName);
        }
                  
        if ($cfg->conf->getSetting( 'site', 'redirect_mobile' ) !== false && ((!isset($_COOKIE['RegularVersion'])  && preg_match("/http_(x_wap|ua)_(.*?)/i",implode(' ',array_keys($_SERVER)))) || ( isset($_COOKIE['RegularVersion']) && $_COOKIE['RegularVersion'] == 2 )) ){
        	erLhcoreClassSystem::instance()->MobileDevice = true;	
        	$optionsSiteAccess = $cfg->conf->getSetting('site_access_options',$cfg->conf->getSetting( 'site', 'redirect_mobile' ));		
        	erLhcoreClassSystem::instance()->Language = $optionsSiteAccess['locale'];                         
            erLhcoreClassSystem::instance()->ThemeSite = $optionsSiteAccess['theme'];                         
            erLhcoreClassSystem::instance()->WWWDirLang = '/'.$cfg->conf->getSetting( 'site', 'redirect_mobile' ); 
            erLhcoreClassSystem::instance()->SiteAccess = $cfg->conf->getSetting( 'site', 'redirect_mobile' ); 
            setcookie('RegularVersion','2',time()+30*24*3600,"/");      // Mobile version   
        } elseif (!isset($_COOKIE['RegularVersion'])){     
                setcookie('RegularVersion','1',time()+30*24*3600,"/");  // Regular version
        }
        
        return erLhcoreClassModule::runModule();
        
    }
    
    
    static function redirect($url = '/')
    {
        header('Location: '. erLhcoreClassDesign::baseurl($url) );
    }
    
    static private $currentModule = NULL;
    static private $currentModuleName = NULL;
    static private $currentView = NULL;
    static private $moduleCacheEnabled = NULL;
    static private $cacheInstance = NULL;
    static private $cacheVersionSite = NULL;
}

?>