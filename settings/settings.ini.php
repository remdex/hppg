<?php

return array (
  'settings' => 
  array (
    'site' => 
    array (
      'title' => 'Site title, title can be overrided by siteaccess',
      'description' => 'Site description, value can be overrided by siteaccess',
      'locale' => 'en_EN',
      'theme' => 'defaulttheme',
      'site_admin_email' => '',
      'site_domain' => '',
      'extract_exif_data' => false,
      'templatecache' => false,
      'templatecompile' => false,
      'modulecompile' => false,
      'classcompile' => false,
      'file_storage_backend' => 'filesystem',         
      'extensions' => array (           
      ), 
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
        6 => 'rus',
        7 => 'ita',
        8 => 'ind',
        9 => 'den',
        10 => 'nor',
        11 => 'jap',
        12 => 'site_admin',
        13 => 'm',
        14 => 'ces',
        15 => 'hrv',
        16 => 'pol',
        17 => 'tur',
        18 => 'ukr',
        19 => 'bel',
        20 => 'sve',
        21 => 'rum',
        22 => 'fin',
        23 => 'ice',
        24 => 'chi',
        25 => 'idn',
        26 => 'gre',
        27 => 'ara',
        28 => 'slv',
        29 => 'srb',
        30 => 'alb',
        31 => 'slo',
        32 => 'lav',
        33 => 'kor',
        34 => 'hun',
        35 => 'est',
      ),
    ),    
    'sitemap_settings' => array (
    
        'siteaccess_sitemaps' => array (
            0 => 'eng',
            1 => 'lit',
            2 => 'ger',
            3 => 'esp',
            4 => 'fre',
            5 => 'por',
            6 => 'rus',
            7 => 'ita',
            8 => 'ind',
            9 => 'den',
            10 => 'nor',
            11 => 'jap',
            12 => 'ces',
            13 => 'hrv',
            14 => 'pol',
            15 => 'tur',
            16 => 'ukr',
            17 => 'bel',
            18 => 'sve',
            19 => 'rum',
            20 => 'fin',
            21 => 'ice',
            22 => 'chi',
            23 => 'idn',
            24 => 'gre',
            25 => 'ara',
            26 => 'slv',
            27 => 'srb',
            28 => 'alb',
            29 => 'slo',
            30 => 'lav',
            31 => 'kor',
            32 => 'hun',
            33 => 'est',
          ),
          
          'categorys_per_page' => 500,
          'album_per_page' => 500,
          'image_per_page' => 1000,
          
          'album_priority' => '0.8',
          'category_priority' => '0.7',
          'image_priority' => '0.9'
    ),
    'site_access_options' => 
    array (
      'eng' => 
      array (
        'locale' => 'en_EN',
        'content_language' => 'en',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'ger' => 
      array (
        'locale' => 'de_DE',
        'content_language' => 'de',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'lit' => 
      array (
        'locale' => 'lt_LT',
        'content_language' => 'lt',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'esp' => 
      array (
        'locale' => 'es_ES',
        'content_language' => 'es',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'fre' => 
      array (
        'locale' => 'fr_FR',
        'content_language' => 'fr',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),     
      'por' => 
      array (
        'locale' => 'pt_PT',
        'content_language' => 'pt',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),     
      'rus' => 
      array (
        'locale' => 'ru_RU',
        'content_language' => 'ru',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),     
      'ita' => 
      array (
        'locale' => 'it_IT',
        'content_language' => 'it',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),     
      'ind' => 
      array (
        'locale' => 'hi_HI',
        'content_language' => 'hi',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),     
      'den' => 
      array (
        'locale' => 'da_DA',
        'content_language' => 'da',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),    
      'nor' => 
      array (
        'locale' => 'no_NO',
        'content_language' => 'no',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),    
      'jap' => 
      array (
        'locale' => 'ja_JA',
        'content_language' => 'ja',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'ces' => 
      array (
        'locale' => 'cs_CS',
        'content_language' => 'cs',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'hrv' => 
      array (
        'locale' => 'hr_HR',
        'content_language' => 'hr',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'ice' => 
      array (
        'locale' => 'is_IS',
        'content_language' => 'is',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'rum' => 
      array (
        'locale' => 'ro_RO',
        'content_language' => 'ro',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'fin' => 
      array (
        'locale' => 'fi_FI',
        'content_language' => 'fi',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'sve' => 
      array (
        'locale' => 'sv_SV',
        'content_language' => 'sv',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'ukr' => 
      array (
        'locale' => 'uk_UK',
        'content_language' => 'uk',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),     
      'bel' => 
      array (
        'locale' => 'be_BE',
        'content_language' => 'be',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'tur' => 
      array (
        'locale' => 'tr_TR',
        'content_language' => 'tr',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'pol' => 
      array (
        'locale' => 'pl_PL',
        'content_language' => 'pl',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'chi' => 
      array (
        'locale' => 'zh_CN',
        'content_language' => 'zh',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),
      'idn' => 
      array (
        'locale' => 'id_ID',
        'content_language' => 'in',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'gre' => 
      array (
        'locale' => 'el_EL',
        'content_language' => 'el',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'ara' => 
      array (
        'locale' => 'ar_AR',
        'content_language' => 'ar',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'slv' => 
      array (
        'locale' => 'sl_SL',
        'content_language' => 'sl',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'srb' => 
      array (
        'locale' => 'sr_SR',
        'content_language' => 'sr',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'alb' => 
      array (
        'locale' => 'sq_SQ',
        'content_language' => 'sq',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'slo' => 
      array (
        'locale' => 'sk_SK',
        'content_language' => 'sk',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'lav' => 
      array (
        'locale' => 'lv_LV',
        'content_language' => 'lv',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'kor' => 
      array (
        'locale' => 'ko_KO',
        'content_language' => 'ko',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'hun' => 
      array (
        'locale' => 'hu_HU',
        'content_language' => 'hu',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ), 
      'est' => 
      array (
        'locale' => 'et_ET',
        'content_language' => 'et',
        'theme' => 
        array (
          0 => 'frontend',
          1 => 'defaulttheme',
        ),
      ),      
      'm' => 
      array (
        'locale' => 'en_EN',
        'content_language' => 'en',
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
        'content_language' => 'en',
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
    	),
    	'full_img_cdn' => ''
    ),
    'face_search' => array (
        'api_key' => 'api_key_of_face_com',
        'api_secret' => 'api_secret_of_face_com',
        'enabled' => false,
        'delay_index' => true,
        'delay_index_portion' => 50,
        'request_delay' => 200,
        'use_full_size' => false
    ),
    'color_search' => array (
        'search_enabled' => true,
        'delay_index' => false,
        'delay_index_portion' => 100,
        'memory_table' => false,
        'minimum_color_match' => 25,
        'maximum_filters' => 8,
        'color_indexer_external' => false,
        'color_indexer_path' => './bin/color_indexer/color_indexer',
        'max_matches' => 1000,
        'database_handler' => true,
        'extended_search' => false,
        'inside_width' => 100,
        'inside_height' => 100,
        'roi_form' => 'rectangle',
    ),
    'sphinx' => 
    array (
      'host' => 'localhost',
      'port' => 3312,     
      'index' => 'index_name index_name_delta',
      'index_forum' => 'index_forum_name index_forum_delta',
      'enabled' => false, 
      'max_matches' => 1000000,
      'enabled_wildcard' => false, 
      'delay_index' => false, 
      'delay_index_portion' => 500,
      'reset_keywords' => array(),
      'ban_keywords' => '',     
      'enable_facet' => false   
    ),
    'cacheEngine' => array(
        'cache_global_key'  => 'global_site_cache_key',
        'className'         => false //'erLhcoreClassLhMemcache erLhcoreClassLhRedis' //false if none
    ),
    'memecache' => 
    array (
      'servers' => array( array('host' => '127.0.0.1',
                                'port' => '11211',     
                                'weight' => 1 ) )     
    ),
    'facebook' => array (
        'enabled' => false,
        'app_id' => '',
        'secret' => ''
    ),
    'amazons3' => array (
        'bucket'            => '',
        'endpoint'          => '',
        'aws_access_key'    => '',
        'aws_secret_key'    => ''
    ),
    'imgseek' => array(
        'database_id' => 1,
        'host' => 'localhost',
        'port' => 31128,
        'enabled' => false,
        'delay_index' => false,
        'delay_index_portion' => 10,
        'query_img_function' => 'queryimgid',
        'query_img_sketch_function' => 'queryimgfile'
    ),
    'redis' => array (
        'server' => array ( 
            'host' => 'localhost', 
            'port' => 6379
        )
    ), 
    'db' => 
    array (
      'host' => 'localhost',
      'user' => '',
      'password' => '',
      'database' => '',
      'port' => 3306,
      'use_slaves' => false,
      'db_slaves' => array(
        array (
         'host' => '',
         'user' => '',
         'port' => 3306,
         'password' => '',
         'database' => '',
        ),
        array (
         'host' => '',
         'user' => '',
         'port' => 3306,
         'password' => '',
         'database' => '',
        )       
      )
    ),
  ),
  'comments' => NULL,
);

?>
