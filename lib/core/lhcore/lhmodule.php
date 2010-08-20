<?php

class erLhcoreClassModule{
    
    static function runModule($Module,$Functions = array())
    {
        if (isset($Module[$GLOBALS['ViewToRun']]))
        {
            
            // Just to start session
            $currentUser = erLhcoreClassUser::instance();
                
            $Params = array();
            $Params['module'] = $Module[$GLOBALS['ViewToRun']];
            $Params['module']['name'] = $GLOBALS['ModuleToRun'];
            $Params['module']['view'] = $GLOBALS['ViewToRun'];
            
            $urlCfgDefault = ezcUrlConfiguration::getInstance();
            $url = erLhcoreClassURL::getInstance();            
            $urlCfgDefault->addUnorderedParameter( 'page' );                                       
            $url->applyConfiguration( $urlCfgDefault );
            $Params['user_parameters_unordered']['page'] = $url->getParam('page');      
            
            if (isset($Module[$GLOBALS['ViewToRun']]['params']))
            {          
                foreach ($Module[$GLOBALS['ViewToRun']]['params'] as $userParameter)
                {           
                   $urlCfgDefault->addOrderedParameter( $userParameter );                                       
                   $url->applyConfiguration( $urlCfgDefault );
                   $Params['user_parameters'][$userParameter] =  $url->getParam($userParameter); 
                }
               
            }
            
            if (isset($Module[$GLOBALS['ViewToRun']]['uparams']))
            {                         
                foreach ($Module[$GLOBALS['ViewToRun']]['uparams'] as $userParameter)
                {           
                   $urlCfgDefault->addUnorderedParameter( $userParameter );                                       
                   $url->applyConfiguration( $urlCfgDefault );

                   $Params['user_parameters_unordered'][$userParameter] =  $url->getParam($userParameter); 
                }
               
            }
            
            // Function set, check permission
            if (isset($Params['module']['functions']))
            {                                 
                if (!$currentUser->hasAccessTo('lh'.$GLOBALS['ModuleToRun'],$Params['module']['functions']))
                {
                   erLhcoreClassModule::redirect('user/login');
                   exit;
                }
            }
            
            if(isset($Params['module']['limitations']))
            {       
                $access = call_user_func($Params['module']['limitations']['self']['method'],$Params['user_parameters'][$Params['module']['limitations']['self']['param']],$currentUser->hasAccessTo('lh'.$GLOBALS['ModuleToRun'],$Params['module']['limitations']['global']));               	
                
                if ($access == false) {                
                	include_once('modules/lhkernel/nopermissionobject.php');  
                   	return $Result;
                } else {
                	$Params['user_object'] = $access;
                }                                
            }

            include_once(erLhcoreClassModule::getModuleFile($GLOBALS['ModuleToRun'],$GLOBALS['ViewToRun'])); 
            
            if (isset($Params['module']['pagelayout']) && !isset($Result['pagelayout'])) {
                $Result['pagelayout'] = $Params['module']['pagelayout'];
            }
               
            return $Result;
        } else {
            erLhcoreClassModule::redirect();
            exit;
        }
    }
    
    
    public static function getModuleFile($module,$view) {
        
        $cfg = erConfigClassLhConfig::getInstance();
        $cacheEnabled = $cfg->conf->getSetting( 'site', 'modulecompile' );
        
        if ($cacheEnabled === false) {
            return 'modules/lh'.$module.'/'.$view.'.php';
        } else {
            
            $cacheWriter = new ezcCacheStorageFileArray(erLhcoreClassSystem::instance()->SiteDir . 'cache/cacheconfig/');    
            $instance = erLhcoreClassSystem::instance();
            
            if (($cacheModules = $cacheWriter->restore('moduleCache_'.$module)) == false)
            {        	
            	$cacheWriter->store('moduleCache_'.$module,array());
            	$cacheModules = array();
            }
        
            
        
            $cacheKey = md5($module.'_'.$view.'_'.$instance->WWWDirLang);
            if (key_exists($cacheKey,$cacheModules))
            {
            		return $cacheModules[$cacheKey];            	
            }
            
            $file = 'modules/lh'.$module.'/'.$view.'.php';          
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
			    } else {
			        $valueReplace = '\''.$valueConfig.'\'';
			    }
			    
				$contentFile = str_replace($Matches[0][$key],$valueReplace,$contentFile);	
			}
			
			file_put_contents($fileCompiled,$contentFile);
            
			$cacheModules[$cacheKey] = $fileCompiled;		
		
			$cacheWriter->store('moduleCache_'.$module,$cacheModules);
						
            return $fileCompiled;
        }
        
    }
   
    
    static function redirect($url = '/')
    {
        header('Location: '. erLhcoreClassDesign::baseurl($url) );
    }
}

?>