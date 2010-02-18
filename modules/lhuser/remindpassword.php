<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/remindpassword.tpl.php');

$msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','Hash not found or was used already');

$hash = $Params['user_parameters']['hash'];

if ($hash != '') {

	$hashData = erLhcoreClassModelForgotPassword::checkHash($hash);

	if ($hashData) {
						
		$UserData = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', (int)$hashData['user_id'] );
			
		if ($UserData) {
			
			$password = erLhcoreClassModelForgotPassword::randomPassword(10);
			$UserData->setPassword($password);
				
			erLhcoreClassUser::getSession()->update($UserData);
							
			$adminEmail = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'site_admin_email' );
				
			$mail = new PHPMailer();
			$mail->CharSet = "UTF-8";
			$mail->From = $adminEmail;
			$mail->FromName = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' );
			$mail->Subject = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','Password remind - new password');
	
			// HTML body
			$body  = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','New password:').' '.$password;
	
			// Plain text body
			$text_body  = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','New password:').' '.$password;		
	
			$mail->Body    = $body;
			$mail->AltBody = $text_body;
			$mail->AddAddress( $UserData->email, $UserData->username);
			
			$mail->Send();			
			$mail->ClearAddresses();
			
			$msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','New password has beend send to your email.');
			
			erLhcoreClassModelForgotPassword::deleteHash($hashData['id']);
			
		}
	}	
} 

$tpl->set('msg',$msg);

$Result['content'] = $tpl->fetch();


?>