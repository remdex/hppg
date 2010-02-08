<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/account.tpl.php' );

if (isset($_POST['Update_account']))
{    
   $definition = array(
        'Password' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'Password1' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'Password1' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'Email' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'Email' ) )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Wrong email address');
    } 
      
    if ( $form->hasInputField( 'Password' ) && (!$form->hasInputField( 'Password1' ) || $form->Password != $form->Password1  ) ) // check for optional field
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Passwords mismatch');
    }
    
    if (count($Errors) == 0)
    {        
        $currentUser = erLhcoreClassUser::instance();
        $UserData = $currentUser->getUserData();

        // Update password if neccesary
        if ($form->hasInputField( 'Password' ) && $form->hasInputField( 'Password1' ) && $form->Password != '' && $form->Password1 != '')
        {
            $UserData->setPassword($form->Password);
        }
        
        $UserData->email   = $form->Email;
          
        erLhcoreClassUser::getSession()->update($UserData);
        $tpl->set('account_updated','done');
        
    }  else {
        $tpl->set('errArr',$Errors);
    }
}

$currentUser = erLhcoreClassUser::instance();

// If already set during account update
if (!isset($UserData))
{    
    $UserData = $currentUser->getUserData();
}

$tpl->set('user',$UserData);
$Result['content'] = $tpl->fetch();



$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('user/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Account data'))
);

?>