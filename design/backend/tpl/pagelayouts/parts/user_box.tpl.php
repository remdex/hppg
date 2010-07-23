<div class="right-infobox">
<?php
$currentUser = erLhcoreClassUser::instance();   
if ($currentUser->isLogged()) : 
$UserData = $currentUser->getUserData();
?>
	<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logged user');?></legend>	
	   <a href="<?=erLhcoreClassDesign::baseurl('/user/account/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>) &raquo;</a><br /> 
	   <a href="<?=erLhcoreClassDesign::baseurl('/user/logout/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?> &raquo;</a>		   	
	</fieldset>
<? 
unset($UserData);
else : ?>
    <fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></legend>		
    	<a href="<?=erLhcoreClassDesign::baseurl('/user/login/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?> &raquo;</a>		   	
    </fieldset>
<?
endif;
unset($currentUser);
?>
</div>