<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/forgotpassword.tpl.php');


if (isset($_POST['Forgotpassword'])) {
    
	$definition = array(
        'Email' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
        )       
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    
    $Errors = array();
    
    if ( !$form->hasValidData( 'Email' ) )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Wrong e-mail address!');
    }
        
	if (count($Errors) == 0) {  
		
		$checkResult = erLhcoreClassModelUser::userEmailExists($form->Email);
		
		if ($checkResult) {
						
			$host = $_SERVER['HTTP_HOST'];	
			
			$adminEmail = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'site_admin_email' );		
			
			$userID = erLhcoreClassModelUser::fetchUserByEmail($form->Email);
			$UserData = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', $userID );
						
			$hash = erLhcoreClassModelForgotPassword::randomPassword(40);

			erLhcoreClassModelForgotPassword::setRemindHash($UserData->id,$hash);	
					
			$mail = new PHPMailer();
			$mail->CharSet = "UTF-8";
			$mail->From = $adminEmail;
			$mail->FromName = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' );
			$mail->Subject = erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Password remind');
		
			// HTML body
			$body  = erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Click this link and to You will be send new password').' -  <a href="http://'.$host.erLhcoreClassDesign::baseurl('/user/remindpassword/').$hash.'">http://'.$host.erLhcoreClassDesign::baseurl('/user/remindpassword/').$hash.'</a>';

			// Plain text body
			$text_body  = erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Click this link and to You will be send new password').' - http://'.$host.erLhcoreClassDesign::baseurl('/user/remindpassword/').$hash;		

			$mail->Body    = $body;
			$mail->AltBody = $text_body;
			$mail->AddAddress( $UserData->email, $UserData->username);

			$mail->Send();			
			$mail->ClearAddresses();

			$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/forgotpasswordsent.tpl.php');		
							
		} else {
			erLhcoreClassModule::redirect('user/forgotpassword');
		}        
    }  else {    	
        $tpl->set('error',$Errors[0]);
    }  
}

$Result['content'] = $tpl->fetch();


?>