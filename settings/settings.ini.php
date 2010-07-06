<?php

return array (
  'settings' => 
  array (
    'site' => 
    array (
      'title' => 'Site title',
      'locale' => 'en_EN',
      'theme' => 'defaulttheme',
      'site_admin_email' => '',
      'templatecache' => false,
      'templatecompile' => false,
      'classCompile' => false,
      'nice_url_enabled' => false,
      'force_virtual_host' => false,
      'etag_caching_enabled' => false,
      'redirect_mobile' => 'm',
      'default_www_user' => 'lighttpd',
      'default_www_group' => 'lighttpd',
      'debug_output' => false,
      'public_category_id' => 28,
      'delay_image_hit_enabled' => false,
      'installed' => false,
      'default_site_access' => 'eng',
      'secrethash' => '',
      'default_url' => 
      array (
        'module' => 'gallery',
        'view' => 'index',
      ),
      'available_site_access' => 
      array (
        0 => 'eng',
        1 => 'lit',
        2 => 'site_admin',
        3 => 'm',
      ),
    ),
    'site_access_options' => 
    array (
      'eng' => 
      array (
        'locale' => 'en_EN',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'lit' => 
      array (
        'locale' => 'lt_LT',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'm' => 
      array (
        'locale' => 'lt_LT',
        'theme' => 
        array (
          0 => 'mobilefrontend',
          1 => 'mobile',
          2 => 'defaulttheme',
        ),
      ),
      'site_admin' => 
      array (
        'locale' => 'en_EN',
        'theme' => 
        array (
          0 => 'backend',
          1 => 'defaulttheme',
        ),
        'login_pagelayout' => 'login',
        'default_url' => 
        array (
          'module' => 'system',
          'view' => 'index',
        ),
      ),
    ),
    'user_settings' => array(
        'default_user_group' => 2,
    ),
    'gallery_settings' => array(
        'default_gallery_category' => 1,
    ),    
    'cdn' => array(
    	'css' => '',
    	'images' => array(
    		0 => '',
    		1 => '' 		
    	)
    ),
    'sphinx' => 
    array (
      'host' => 'localhost',
      'port' => 3312,     
      'index' => 'index_name',
      'enabled' => false,      
      'max_matches' => 1000000,      
    ),
    'cacheEngine' => array(
        'cache_global_key'  => 'global_site_cache_key',
        'className'         => false //'erLhcoreClassLhMemcache' //false if none
    ),
    'memecache' => 
    array (
      'servers' => array( array('host' => '127.0.0.1',
                                'port' => '11211',     
                                'weight' => 1 ) )     
    ), 
    'db' => 
    array (
      'host' => 'localhost',
      'user' => '',
      'password' => '',
      'database' => '',
      'port' => 3306,
    ),
  ),
  'comments' => NULL,
);

?>
