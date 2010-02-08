<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/loginadmin.tpl.php');

if (isset($_POST['Login']))
{
    $currentUser = erLhcoreClassUser::instance();
    
    if (!$currentUser->authenticate($_POST['Username'],$_POST['Password']))
    {     
            $Error = erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Incorrect username or password');
            $tpl->set('error',$Error);   
    } else {
        // Redirect to front    
        erLhcoreClassModule::redirect('system/index');
        exit ;
    }    
}

$Result['content'] = $tpl->fetch();
$Result['pagelayout'] = 'login';

?>