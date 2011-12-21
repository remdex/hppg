<?php
$facebook = new Facebook(array(
  'appId'  => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'app_id' ),
  'secret' => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'secret' ) ,
));

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/completefblogin.tpl.php');    
$userFB = $facebook->getUser();



if ($userFB) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
//    $user_profile = $facebook->api('/me');
    
    $users = erLhcoreClassModelUserFB::getList(array('filter' => array('fb_user_id' => $userFB)));
    $userGallery = false;
    if (is_array($users) && count($users)){
        $userGallery = array_shift($users);
    }
    
    $currentUser = erLhcoreClassUser::instance();  
       
    if ( $userGallery === false ) {
        $tpl->set('multiple_action',true);
        $tpl->set('create_account',3);
                  
        if ($currentUser->isLogged()){
            $tpl->set('map_to_current',true);
            $tpl->set('current_user',$currentUser->getUserData());
            $tpl->set('create_account',2);
        }
        
        if (isset($_POST['MapAccounts']) && isset($_POST['CreateAccount']) && $_POST['CreateAccount'] == 2) {
    
            if ($currentUser->isLogged()) {
                
                $user_profile = $facebook->api('/me');
                
                $fbgaluser = new erLhcoreClassModelUserFB();
                $fbgaluser->user_id = $currentUser->getUserID();
                $fbgaluser->fb_user_id = $userFB;
                $fbgaluser->name = $user_profile['name'];
                $fbgaluser->link = $user_profile['link'];                
                $fbgaluser->saveThis();

                erLhcoreClassUser::instance()->setLoggedUserInstantly($currentUser->getUserID());
                erLhcoreClassModule::redirect('user/account');
                exit;
            }
            
        // User decided to create account
        } elseif (isset($_POST['MapAccounts']) && isset($_POST['CreateAccount']) && $_POST['CreateAccount'] == 1) {
            
            $user_profile = $facebook->api('/me');            
            $usernameFilled = false;
         
            if (!erLhcoreClassModelUser::userExists($user_profile['name'])){
                $username = $user_profile['name'];
                $usernameFilled = true;                    
            } else {
                $username = $user_profile['name'];
            } 
         
            $UserData = new erLhcoreClassModelUser(); 
     
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
            $GroupUser->group_id = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'default_user_group' ); //User group
            $GroupUser->user_id = $UserData->id;        
            erLhcoreClassUser::getSession()->save($GroupUser);
            
            $CacheManager = erConfigClassLhCacheConfig::getInstance();
            $CacheManager->expireCache();
                
            try {
                $defaultUserCategoryParent = erLhcoreClassModelGalleryCategory::fetch(erConfigClassLhConfig::getInstance()->getSetting( 'gallery_settings', 'default_gallery_category' ));
                $userCategory = new erLhcoreClassModelGalleryCategory();
                $userCategory->owner_id =  $UserData->id;
                $userCategory->parent =  $defaultUserCategoryParent->cid;
                $userCategory->name =  $UserData->username;
                erLhcoreClassGallery::getSession()->save($userCategory);               
                $userCategory->clearCategoryCache();
            } catch (Exception $e) { // Perhaps administrator deleted default gallery category
                // Do nothing
            }
                 
            $fbgaluser = new erLhcoreClassModelUserFB();
            $fbgaluser->user_id = $UserData->id;
            $fbgaluser->fb_user_id = $userFB;
            $fbgaluser->name = $user_profile['name'];
            $fbgaluser->link = $user_profile['link'];                
            $fbgaluser->saveThis();
              
            erLhcoreClassUser::instance()->setLoggedUserInstantly($UserData->id);        
            erLhcoreClassModule::redirect('user/account');
            exit;
            
        } elseif (isset($_POST['MapAccounts']) &&  $_POST['CreateAccount'] == 3) {
            $currentUser = erLhcoreClassUser::instance();
            if ($currentUser->authenticate($_POST['Username'],$_POST['Password']))
            {
                $user_profile = $facebook->api('/me');
                
                $fbgaluser = new erLhcoreClassModelUserFB();
                $fbgaluser->user_id = $currentUser->getUserID();
                $fbgaluser->fb_user_id = $userFB;
                $fbgaluser->name = $user_profile['name'];
                $fbgaluser->link = $user_profile['link'];                
                $fbgaluser->saveThis();
                
                erLhcoreClassUser::instance()->setLoggedUserInstantly($currentUser->getUserID());
                erLhcoreClassModule::redirect('user/account');
                
                exit;
            } else {
                $tpl->set('failed_authenticate',true);
            }
        }
    
    } else {

        erLhcoreClassUser::instance()->setLoggedUserInstantly( $userGallery->user_id);        
        erLhcoreClassModule::redirect('user/account');
        exit;
       
        $tpl->set('create_account',3);  
    }
    
    //$albums_profile = $facebook->api('100001507411398/albums');        
  } catch (FacebookApiException $e) {
    erLhcoreClassModule::redirect('user/login');
    exit;
  }
} else {
    erLhcoreClassModule::redirect('user/login');
    exit;
}


$Result['content'] = $tpl->fetch();