<?php

$facebook = new Facebook(array(
  'appId'  => erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'app_id' ),
  'secret' => erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'secret' ) ,
));

$userFB = $facebook->getUser();

if ($userFB) {
    $tpl = erLhcoreClassTemplate::getInstance( 'lhfb/albums.tpl.php' );
    
    $facebook = new Facebook(array(
      'appId'  => erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'app_id' ),
      'secret' => erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'secret' ) ,
    ));
    
    $user_profile = $facebook->api('/me');
    $tpl->set('profile',$user_profile);
    $tpl->set('facebook',$facebook);
    
    if ($user_profile) {
        $albums_profile = $facebook->api($user_profile['id'] . '/albums');  
        $tpl->set('albums',$albums_profile);
    }
    
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(
    array('url' => erLhcoreClassDesign::baseurl('user/index'),
    'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
    array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Facebook albums')));
} else {
    $tpl = erLhcoreClassTemplate::getInstance( 'lhfb/login.tpl.php' );
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(
    array('url' => erLhcoreClassDesign::baseurl('user/index'),
    'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
    array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Facebook session has expired, please login')));
}