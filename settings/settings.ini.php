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
      'modulecompile' => false,        
      'extensions' => array (           
      ),
      'classCompile' => false,
      'nice_url_enabled' => false,
      'force_virtual_host' => false,
      'etag_caching_enabled' => false,
      'redirect_mobile' => false,//'m',
      'imagemagic_enabled' => false,
      'default_www_user' => 'lighttpd',
      'default_www_group' => 'lighttpd',
      'StorageDirPermissions' => 0777,
	  'StorageFilePermissions' => 0666,
      'debug_output' => false,
      'resolutions' => array('640x480' =>   array('width' => 640, 'height' => 480),
                             '800x600' =>   array('width' => 800, 'height' => 600),
                             '1024x768' =>  array('width' => 1024,'height' => 768),
                             '1152x864' =>  array('width' => 1152,'height' => 864),
                             '1280x768' =>  array('width' => 1280,'height' => 768),
                             '1280x800' =>  array('width' => 1280,'height' => 800),
                             '1280x960' =>  array('width' => 1280,'height' => 960),
                             '1280x1024' => array('width' => 1280,'height' => 1024),
                             '1366x768' =>  array('width' => 1366,'height' => 768),
                             '1360x768' =>  array('width' => 1360,'height' => 768),
                             '1440x900' =>  array('width' => 1440,'height' => 900),
                             '1600x1200' => array('width' => 1600,'height' => 1200),
                             '1600x900' =>  array('width' => 1600,'height' => 900),
                             '1680x1050' => array('width' => 1680,'height' => 1050),
                             '1920x1200' => array('width' => 1920,'height' => 1200),
                             '1920x1080' => array('width' => 1920,'height' => 1080),
      ),
      'public_category_id' => 28,
      'delay_image_hit_enabled' => false,
      'delay_image_hit_log' => false,
      'delay_image_hit_log_settings' => array (      
        'host' => false,                               //It can be false or array, ex. array('example.com','www.example.com')
        'log_path' => '' // Absolute path to log, ex. /var/log/lighttpd/access.log.1     
      ), 
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
        2 => 'ger',
        3 => 'esp',
        4 => 'fre',
        5 => 'por',
        6 => 'site_admin',
        7 => 'm',
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
      'ger' => 
      array (
        'locale' => 'de_DE',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'por' => 
      array (
        'locale' => 'pt_PT',
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
      'esp' => 
      array (
        'locale' => 'es_ES',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'fre' => 
      array (
        'locale' => 'fr_FR',
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
        'locale' => 'en_EN',
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
        'anonymous_user_id' => 2,
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
      'enabled_wildcard' => false        
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
