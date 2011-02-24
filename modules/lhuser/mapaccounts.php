<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/mapaccounts.tpl.php');

$users = erLhcoreClassModelUser::getUserList(array('filter' => array('email' => $_SESSION['open_id_email'])));
$user = false;
if (is_array($users) && count($users)){
    $user = array_shift($users);
}

// Instantly register user with provided data
if ($user === false && isset($_SESSION['open_id_identity_url']) && isset($_SESSION['open_id_type'])){
        
    $usernameFilled = false;
    $username = 'user_'.time();    
    if (isset($_SESSION['open_id_email'])) {        
        $usernameParts = explode('@',$_SESSION['open_id_email']);
        if (count($usernameParts) == 2 && $usernameParts[0] != ''){
            
            if (!erLhcoreClassModelUser::userExists($usernameParts[0])){
                $username = $usernameParts[0];
                $usernameFilled = true;
                
            } else {
                $username = $usernameParts[0];
            } 
        }
    }
    
    $UserData = new erLhcoreClassModelUser(); 
    $UserData->email = $_SESSION['open_id_email'];
    
    if ($usernameFilled == true){
        $UserData->username = $username;
    }
    
    erLhcoreClassUser::getSession()->save($UserData); 
            
    if ($usernameFilled == false){
        $UserData->username = $username.'_'.$UserData->id;
        
        if (!erLhcoreClassModelUser::userExists($UserData->username)) {
                erLhcoreClassUser::getSession()->update($UserData); 
        }
    }    
    
    $GroupUser = new erLhcoreClassModelGroupUser();        
    $GroupUser->group_id = erConfigClassLhConfig::getInstance()->conf->getSetting( 'user_settings', 'default_user_group' ); //User group
    $GroupUser->user_id = $UserData->id;        
    erLhcoreClassUser::getSession()->save($GroupUser);
    
    $CacheManager = erConfigClassLhCacheConfig::getInstance();
    $CacheManager->expireCache();
            
    try {
        $defaultUserCategoryParent = erLhcoreClassModelGalleryCategory::fetch(erConfigClassLhConfig::getInstance()->conf->getSetting( 'gallery_settings', 'default_gallery_category' ));
        $userCategory = new erLhcoreClassModelGalleryCategory();
        $userCategory->owner_id =  $UserData->id;
        $userCategory->parent =  $defaultUserCategoryParent->cid;
        $userCategory->name =  'User-'.$UserData->id;
        erLhcoreClassGallery::getSession()->save($userCategory);               
        $userCategory->clearCategoryCache();
    } catch (Exception $e) { // Perhaps administrator deleted default gallery category
        // Do nothing
    }
            
    $oidMap = new erLhcoreClassModelOidMap();
    $oidMap->user_id = $UserData->id;
    $oidMap->open_id = $_SESSION['open_id_identity_url'];
    $oidMap->open_id_type = $_SESSION['open_id_type'];
    $oidMap->email = $UserData->email;
    $oidMap->saveThis();
            
    unset($_SESSION['open_id_type']);
    unset($_SESSION['open_id_identity_url']);
    unset($_SESSION['open_id_email']);
    
    erLhcoreClassUser::instance()->setLoggedUserInstantly($UserData->id);        
    erLhcoreClassModule::redirect('user/account');
    exit;
}

if (isset($_POST['MapAccounts']) ) {

    $currentUser = erLhcoreClassUser::instance();
    if ($currentUser->authenticate($_POST['Username'],$_POST['Password']))
    {
        $oidMap = new erLhcoreClassModelOidMap();
        $oidMap->user_id = $currentUser->getUserID();
        $oidMap->open_id = $_SESSION['open_id_identity_url'];
        $oidMap->open_id_type = $_SESSION['open_id_type'];
        $oidMap->email = $_SESSION['open_id_email'];
        $oidMap->saveThis();
        erLhcoreClassUser::instance()->setLoggedUserInstantly($currentUser->getUserID());     

        unset($_SESSION['open_id_type']);
        unset($_SESSION['open_id_identity_url']);
        unset($_SESSION['open_id_email']);

        erLhcoreClassModule::redirect('user/account');
        exit;
    } else {
        $tpl->set('failed_authenticate',true);
    }

}

$tpl->set('user',$user);


$Result['content'] = $tpl->fetch();


?>