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
                if (!$currentUser->isLogged()){
                    header('Location: '. erLhcoreClassSystem::instance()->WWWDir . '/user/login');
                    return ;
                }
                
                if (!$currentUser->hasAccessTo('lh'.$GLOBALS['ModuleToRun'],$Params['module']['functions']))
                {
                    include_once('modules/lhkernel/nopermission.php');  
                    return $Result;
                }
            }
            
            if(isset($Params['module']['limitations']))
            {            
//            	if (!$currentUser->isLogged()){
//                    header('Location: '. erLhcoreClassSystem::instance()->WWWDir . '/user/login');
//                    return ;
//                }
                               
                $access = call_user_func($Params['module']['limitations']['self']['method'],$Params['user_parameters'][$Params['module']['limitations']['self']['param']],$currentUser->hasAccessTo('lh'.$GLOBALS['ModuleToRun'],$Params['module']['limitations']['global']));               	
                
                if ($access == false) {                
                	include_once('modules/lhkernel/nopermissionobject.php');  
                   	return $Result;
                } else {
                	$Params['user_object'] = $access;
                }                                
            }
                         
            include_once('modules/lh'.$GLOBALS['ModuleToRun'].'/'.$GLOBALS['ViewToRun'].'.php'); 
            
            if (isset($Params['module']['pagelayout']) && !isset($Result['pagelayout'])) {
                $Result['pagelayout'] = $Params['module']['pagelayout'];
            }
        
            
                       
            return $Result;
        } else {
            echo 'Views not found -> ',$GLOBALS['ViewToRun'];
        }
    }
    
    static function redirect($url = '/')
    {        
        header('Location: '. erLhcoreClassSystem::instance()->WWWDir . erLhcoreClassSystem::instance()->WWWDirLang . '/' .ltrim($url,'/') );
    }
}

?>