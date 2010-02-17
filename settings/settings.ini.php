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
      'etag_caching_enabled' => false,
      'installed' => false,
      'secrethash' => '',
      'available_locales' => 
      array (
        0 => 'en_EN',
        1 => 'lt_LT',
      ),
    ),
    'user_settings' => array(
        'default_user_group' => 2,
    ),
    'sphinx' => 
    array (
      'host' => 'localhost',
      'port' => 3312,     
      'index' => 'index_name',
      'enabled' => false,      
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
