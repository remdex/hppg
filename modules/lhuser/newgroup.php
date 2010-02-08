<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/newgroup.tpl.php');

$GroupData = new erLhcoreClassModelGroup();

if (isset($_POST['Save_group']) || isset($_POST['Save_group_and_assign_user']))
{    
   $definition = array(

        'Name' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        )

        );
    
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
        
    if ( !$form->hasValidData( 'Name' ) || $form->Name == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter group name');
    }
    
    if (count($Errors) == 0)
    {  
        $GroupData->name    = $form->Name;
       
        erLhcoreClassUser::getSession()->save($GroupData);
        
        if (isset($_POST['Save_group_and_assign_user']))
            erLhcoreClassModule::redirect('user/editgroup/' . $GroupData->id . '/?adduser=1');
        else 
            erLhcoreClassModule::redirect('user/grouplist');
            
            
        return ;
        
    }  else {
                
        $GroupData->name = $form->Name;
        
        $tpl->set('errArr',$Errors);
    }
}

$tpl->set('group',$GroupData);

$Result['content'] = $tpl->fetch();


$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','System configuration')),

array('url' => erLhcoreClassDesign::baseurl('user/grouplist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','Groups')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','New group'))
)

?>