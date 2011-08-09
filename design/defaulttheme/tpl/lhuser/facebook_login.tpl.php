<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'app_id' ),
  'secret' => erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'secret' ) ,
));

/*// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    $albums_profile = $facebook->api('100001507411398/albums');
    
    
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}*/

  $loginUrl = $facebook->getLoginUrl(array('scope' => 'user_photos','redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('user/completefblogin') ));
?>

<div class="left">
<br />
    
      <div>
        <a href="<?php echo $loginUrl; ?>"><img src="<?=erLhcoreClassDesign::design('images/gallery/loginfacebook.png')?>" alt="Login with facebook" title="Login with facebook"> </a>        
      </div>

</div>