<?php

try {
	
$cfgSite = erConfigClassLhConfig::getInstance();

if ($cfgSite->conf->getSetting( 'site', 'installed' ) == true)
{
    $Params['module']['functions'] = array('install');
    include_once('modules/lhkernel/nopermission.php'); 
     
    $Result['pagelayout'] = 'install';
    $Result['path'] = array(array('title' => 'High performance photo gallery install'));
    return $Result;
    
    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhinstall/install1.tpl.php');

switch ((int)$Params['user_parameters']['step_id']) {
    
	case '1':
		$Errors = array();		
		if (!is_writable("cache/cacheconfig/settings.ini.php"))
	       $Errors[] = "cache/cacheconfig/settings.ini.php is not writable";	
	              
		if (!is_writable("cache/translations"))
	       $Errors[] = "cache/translations is not writable"; 
	       	           
		if (!is_writable("cache/userinfo"))
	       $Errors[] = "cache/userinfo is not writable";
	          	           
		if (!is_writable("albums"))
	       $Errors[] = "albums is not writable";
	             	           
		if (!is_writable("albums/userpics"))
	       $Errors[] = "albums/userpics is not writable";
	              
		if (!is_writable("var/archives"))
	       $Errors[] = "var/archives is not writable";
	          	           
		if (!is_writable("var/tmpfiles"))
	       $Errors[] = "var/tmpfiles is not writable";
	             	           
		if (!is_writable("var/watermark"))
	       $Errors[] = "var/watermark is not writable";	
	           
	       if (count($Errors) == 0)
	           $tpl->setFile('lhinstall/install2.tpl.php');	              
	  break;
	  
	  case '2':
		$Errors = array();	
			
		$definition = array(
            'DatabaseUsername' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
            'DatabasePassword' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
            'DatabaseHost' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
            'DatabasePort' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'int'
            ),
            'DatabaseDatabaseName' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
        );
	     	       
	   $form = new ezcInputForm( INPUT_POST, $definition ); 
	      
	   
	   if ( !$form->hasValidData( 'DatabaseUsername' ) || $form->DatabaseUsername == '' )
       {
           $Errors[] = 'Please enter database username';
       }   
	   
	   if ( !$form->hasValidData( 'DatabasePassword' ) || $form->DatabasePassword == '' )
       {
           $Errors[] = 'Please enter database password';
       } 
       
	   if ( !$form->hasValidData( 'DatabaseHost' ) || $form->DatabaseHost == '' )
       {
           $Errors[] = 'Please enter database host';
       }  
       
	   if ( !$form->hasValidData( 'DatabasePort' ) || $form->DatabasePort == '' )
       {
           $Errors[] = 'Please enter database post';
       }
       
	   if ( !$form->hasValidData( 'DatabaseDatabaseName' ) || $form->DatabaseDatabaseName == '' )
       {
           $Errors[] = 'Please enter database name';
       }
       
       if (count($Errors) == 0)
       { 
           try {
           $db = ezcDbFactory::create( "mysql://{$form->DatabaseUsername}:{$form->DatabasePassword}@{$form->DatabaseHost}:{$form->DatabasePort}/{$form->DatabaseDatabaseName}" );
           } catch (Exception $e) {     
                  $Errors[] = 'Cannot login with provided logins. Returned message: <br/>'.$e->getMessage();
           }
       }
	    
	       if (count($Errors) == 0){
	           
	           $cfgSite = erConfigClassLhConfig::getInstance();
	           $cfgSite->conf->setSetting( 'db', 'host', $form->DatabaseHost);
	           $cfgSite->conf->setSetting( 'db', 'user', $form->DatabaseUsername);
	           $cfgSite->conf->setSetting( 'db', 'password', $form->DatabasePassword);
	           $cfgSite->conf->setSetting( 'db', 'database', $form->DatabaseDatabaseName);
	           $cfgSite->conf->setSetting( 'db', 'port', $form->DatabasePort);
	           
	           $cfgSite->conf->setSetting( 'site', 'secrethash', substr(md5(time() . ":" . mt_rand()),0,10));
	           
	           $cfgSite->save();
	                 
	           $tpl->setFile('lhinstall/install3.tpl.php');	
	       } else {
	           
	          $tpl->set('db_username',$form->DatabaseUsername);
	          $tpl->set('db_password',$form->DatabasePassword);
	          $tpl->set('db_host',$form->DatabaseHost);
	          $tpl->set('db_port',$form->DatabasePort);
	          $tpl->set('db_name',$form->DatabaseDatabaseName);
	          
	          $tpl->set('errors',$Errors);
	          $tpl->setFile('lhinstall/install2.tpl.php');	  
	       }           
	  break;

	case '3':
	    
	    $Errors = array();	

	    if ($_SERVER['REQUEST_METHOD'] == 'POST')
	    {	
    		$definition = array(
                'AdminUsername' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'string'
                ),
                'AdminPassword' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'string'
                ),
                'AdminPassword1' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'string'
                ),
                'AdminEmail' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
                )
            );
    	
    	    $form = new ezcInputForm( INPUT_POST, $definition ); 
    
    	        
    	    if ( !$form->hasValidData( 'AdminUsername' ) || $form->AdminUsername == '')
            {
                $Errors[] = 'Please enter admin username';
            }  
            
            if ($form->hasValidData( 'AdminUsername' ) && $form->AdminUsername != '' && strlen($form->AdminUsername) > 10)
            {
                $Errors[] = 'Maximum 10 characters for admin username';
            }
               
    	    if ( !$form->hasValidData( 'AdminPassword' ) || $form->AdminPassword == '')
            {
                $Errors[] = 'Please enter admin password';
            }    
            
    	    if ($form->hasValidData( 'AdminPassword' ) && $form->AdminPassword != '' && strlen($form->AdminPassword) > 10)
            {
                $Errors[] = 'Maximum 10 characters for admin password';
            }        
                    
    	    if ($form->hasValidData( 'AdminPassword' ) && $form->AdminPassword != '' && strlen($form->AdminPassword) <= 10 && $form->AdminPassword1 != $form->AdminPassword)
            {
                $Errors[] = 'Passwords missmatch';
            } 
           
                   
    	    if ( !$form->hasValidData( 'AdminEmail' ) )
            {
                $Errors[] = 'Wrong email address';
            } 
                                  
            if (count($Errors) == 0) {
                
               $tpl->set('admin_username',$form->AdminUsername);               
               if ( $form->hasValidData( 'AdminEmail' ) ) $tpl->set('admin_email',$form->AdminEmail);                     
    	      
    	        
    	       $db = ezcDbInstance::get();	       
    	                      
               //Groups table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_group` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) NOT NULL,
                  PRIMARY KEY (`id`)
                ) TYPE=MyISAM");
               
               // Administrators group
               $GroupData = new erLhcoreClassModelGroup();
               $GroupData->name    = "Administrators";
               erLhcoreClassUser::getSession()->save($GroupData);
               
               // Registered users group
               $GroupDataRegistered = new erLhcoreClassModelGroup();
               $GroupDataRegistered->name    = "Registered users";
               erLhcoreClassUser::getSession()->save($GroupDataRegistered);
               
               // Anonymous users group
               $GroupDataAnonymous = new erLhcoreClassModelGroup();
               $GroupDataAnonymous->name    = "Anonymous users group";
               erLhcoreClassUser::getSession()->save($GroupDataAnonymous);
                              
               // Roles table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_role` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) NOT NULL,
                  PRIMARY KEY (`id`)
                ) TYPE=MyISAM");
               
               // Administrators role
               $Role = new erLhcoreClassModelRole();
               $Role->name = 'Administrators';
               erLhcoreClassRole::getSession()->save($Role);
               
               // Registered users role
               $RoleRegistered = new erLhcoreClassModelRole();
               $RoleRegistered->name = 'Registered users';
               erLhcoreClassRole::getSession()->save($RoleRegistered);

               // Anonymous users role
               $RoleAnonymous = new erLhcoreClassModelRole();
               $RoleAnonymous->name = 'Anonymous users';
               erLhcoreClassRole::getSession()->save($RoleAnonymous);
               
               
               //Assing group to role table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_grouprole` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `group_id` int(11) NOT NULL,
                  `role_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `group_id` (`role_id`,`group_id`)
                ) TYPE=MyISAM");

               // Admin group assing admin role
               $GroupRole = new erLhcoreClassModelGroupRole();        
               $GroupRole->group_id =$GroupData->id;
               $GroupRole->role_id = $Role->id;        
               erLhcoreClassRole::getSession()->save($GroupRole);
        
               // Registered users role assign registered users role
               $GroupRoleRegistered = new erLhcoreClassModelGroupRole();        
               $GroupRoleRegistered->group_id = $GroupDataRegistered->id;
               $GroupRoleRegistered->role_id = $RoleRegistered->id;        
               erLhcoreClassRole::getSession()->save($GroupRoleRegistered);
               
               // Assign registered users anonymous users role
               $GroupRoleRegisteredAnonymous = new erLhcoreClassModelGroupRole();        
               $GroupRoleRegisteredAnonymous->group_id = $GroupDataRegistered->id;
               $GroupRoleRegisteredAnonymous->role_id = $RoleAnonymous->id;        
               erLhcoreClassRole::getSession()->save($GroupRoleRegisteredAnonymous);
                              
               // Anonymous users assing anonymous users role
               $GroupRoleAnonymous = new erLhcoreClassModelGroupRole();        
               $GroupRoleAnonymous->group_id = $GroupDataAnonymous->id;
               $GroupRoleAnonymous->role_id = $RoleAnonymous->id;        
               erLhcoreClassRole::getSession()->save($GroupRoleAnonymous);
               
               
               // Users
               $db->query("CREATE TABLE IF NOT EXISTS `lh_users` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `username` varchar(40) NOT NULL,
                      `password` varchar(40) NOT NULL,
                      `email` varchar(100) NOT NULL,
                      `lastactivity` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) TYPE=MyISAM");
               
               // Forgot password table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_forgotpasswordhash` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `user_id` int(11) NOT NULL,
                      `hash` varchar(100) NOT NULL,
                      `created` int(11) NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `user_id` (`user_id`),
                      KEY `hash` (`hash`)
                    ) ENGINE=MyISAM");
                              
                // Create admin user
                $UserData = new erLhcoreClassModelUser();
                $UserData->setPassword($form->AdminPassword);
                $UserData->email   = $form->AdminEmail;             
                $UserData->username = $form->AdminUsername;        
                erLhcoreClassUser::getSession()->save($UserData);

                // Create anonymous user
                $UserDataAnonymous = new erLhcoreClassModelUser();
                $UserDataAnonymous->setPassword(erLhcoreClassModelForgotPassword::randomPassword());
                $UserDataAnonymous->email   = $form->AdminEmail;             
                $UserDataAnonymous->username = 'anonymous';        
                erLhcoreClassUser::getSession()->save($UserDataAnonymous);                
                
                // User assign to groyp table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_groupuser` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `group_id` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `group_id` (`group_id`),
                  KEY `user_id` (`user_id`),
                  KEY `group_id_2` (`group_id`,`user_id`)
                ) TYPE=MyISAM ");

                // Assign admin user to admin group
                $GroupUser = new erLhcoreClassModelGroupUser();        
                $GroupUser->group_id = $GroupData->id;
                $GroupUser->user_id = $UserData->id;        
                erLhcoreClassUser::getSession()->save($GroupUser);
                
                // Assign Anonymous user to anonymous group
                $GroupUserAnonymous = new erLhcoreClassModelGroupUser();        
                $GroupUserAnonymous->group_id = $GroupDataAnonymous->id;
                $GroupUserAnonymous->user_id = $UserDataAnonymous->id;        
                erLhcoreClassUser::getSession()->save($GroupUserAnonymous);
                 
                //Assign default role functions
                $db->query("CREATE TABLE IF NOT EXISTS `lh_rolefunction` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `role_id` int(11) NOT NULL,
                  `module` varchar(100) NOT NULL,
                  `function` varchar(100) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `role_id` (`role_id`)
                ) TYPE=MyISAM");
                
                // Gallery queries
                
                // Albums table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_albums` (
                  `aid` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `pos` int(11) NOT NULL DEFAULT '0',
                  `category` int(11) NOT NULL DEFAULT '0',
                  `keyword` varchar(50) DEFAULT NULL,
                  `owner_id` int(11) NOT NULL,
                  `public` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`aid`),
                  KEY `alb_category` (`category`),
                  KEY `owner_id` (`owner_id`)
                ) ENGINE=MyISAM");
                
                // Categorys
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_categorys` (
                  `cid` int(11) NOT NULL AUTO_INCREMENT,
                  `owner_id` int(11) NOT NULL DEFAULT '0',
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `pos` int(11) NOT NULL DEFAULT '0',
                  `parent` int(11) NOT NULL DEFAULT '0',
                  `hide_frontpage` int(11) NOT NULL,
                  `has_albums` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`cid`),
                  KEY `cat_parent` (`parent`),
                  KEY `cat_pos` (`pos`),
                  KEY `cat_owner_id` (`owner_id`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
                
                // Comments
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_comments` (
                          `pid` mediumint(10) NOT NULL DEFAULT '0',
                          `msg_id` mediumint(10) NOT NULL AUTO_INCREMENT,
                          `msg_author` varchar(25) NOT NULL DEFAULT '',
                          `msg_body` text NOT NULL,
                          `msg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                          `msg_raw_ip` tinytext,
                          `msg_hdr_ip` tinytext,
                          `author_md5_id` varchar(32) NOT NULL DEFAULT '',
                          `author_id` int(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (`msg_id`),
                          KEY `com_pic_id` (`pid`)
                        ) ENGINE=MyISAM");
                
                // Images table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_images` (
                          `pid` int(11) NOT NULL AUTO_INCREMENT,
                          `aid` int(11) NOT NULL DEFAULT '0',
                          `filepath` varchar(255) NOT NULL DEFAULT '',
                          `filename` varchar(255) NOT NULL DEFAULT '',
                          `filesize` int(11) NOT NULL DEFAULT '0',
                          `total_filesize` int(11) NOT NULL DEFAULT '0',
                          `pwidth` smallint(6) NOT NULL DEFAULT '0',
                          `pheight` smallint(6) NOT NULL DEFAULT '0',
                          `hits` int(10) NOT NULL DEFAULT '0',
                          `ctime` int(11) NOT NULL DEFAULT '0',
                          `owner_id` int(11) NOT NULL DEFAULT '0',
                          `pic_rating` int(11) NOT NULL DEFAULT '0',
                          `votes` int(11) NOT NULL DEFAULT '0',
                          `title` varchar(255) NOT NULL DEFAULT '',
                          `caption` text NOT NULL,
                          `keywords` varchar(255) NOT NULL DEFAULT '',
                          `pic_raw_ip` tinytext,
                          `approved` int(11) NOT NULL DEFAULT '0',
                          `mtime` int(11) NOT NULL,
                          `comtime` int(11) NOT NULL,
                          `sort_rated` bigint(20) NOT NULL,
                          `anaglyph` int(11) NOT NULL DEFAULT '0',
                          `rtime` int(11) NOT NULL,
                          PRIMARY KEY (`pid`),
                          KEY `owner_id` (`owner_id`),
                          KEY `pic_hits` (`hits`),
                          KEY `pic_rate` (`pic_rating`),
                          KEY `pic_aid` (`aid`),
                          KEY `mtime` (`mtime`),
                          KEY `pid_3` (`ctime`),
                          KEY `aid_4` (`aid`,`pwidth`,`pheight`,`comtime`,`pid`),
                          KEY `approved` (`approved`,`pid`),
                          KEY `pid_12` (`pwidth`,`pheight`,`approved`,`pid`),
                          KEY `pid_4` (`approved`,`hits`,`pid`),
                          KEY `pid_4res` (`pwidth`,`pheight`,`approved`,`hits`,`pid`),
                          KEY `pid_5` (`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `pwidth_2` (`pwidth`,`pheight`,`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `pid` (`approved`,`mtime`,`pid`),
                          KEY `pwidth` (`pwidth`,`pheight`,`approved`,`mtime`,`pid`),
                          KEY `comtime` (`approved`,`comtime`,`pid`),
                          KEY `pid_com_res` (`pwidth`,`pheight`,`approved`,`comtime`,`pid`),
                          KEY `pid_7` (`aid`,`approved`,`hits`,`pid`),
                          KEY `pid_6` (`aid`,`approved`,`pid`),
                          KEY `pid_8` (`aid`,`approved`,`mtime`,`pid`),
                          KEY `pid_9` (`aid`,`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `pid_10` (`aid`,`approved`,`comtime`,`pid`),
                          KEY `aid` (`aid`,`pwidth`,`pheight`,`approved`,`pid`),
                          KEY `pid_2` (`ctime`,`approved`,`pid`),
                          KEY `pid_11` (`aid`,`pwidth`,`pheight`,`approved`,`hits`,`pid`),
                          KEY `aid_2` (`aid`,`pwidth`,`pheight`,`approved`,`mtime`,`pid`),
                          KEY `aid_3` (`aid`,`pwidth`,`pheight`,`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `approved_2` (`approved`),
                          KEY `rated_gen` (`approved`,`rtime`,`pid`),
                          KEY `rated_gen_res` (`pwidth`,`pheight`,`approved`,`rtime`,`pid`),
                          KEY `a_rated_gen_res` (`aid`,`pwidth`,`pheight`,`approved`,`rtime`,`pid`),
                          KEY `a_rated_gen` (`aid`,`approved`,`rtime`,`pid`)
                        ) ENGINE=MyISAM;");
                
                
                // Last search table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_lastsearch` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `countresult` int(11) NOT NULL,
                          `keyword` varchar(255) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM");
                
                // Public upload sessions
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_upload` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `album_id` int(11) NOT NULL,
                  `hash` varchar(40) NOT NULL,
                  `created` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_upload_archive` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `filename` varchar(200) NOT NULL,
				  `album_id` int(11) NOT NULL,
				  `album_name` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `keywords` varchar(200) NOT NULL,
				  `user_id` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
                                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_myfavorites_images` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `session_id` int(11) NOT NULL,
				  `pid` int(11) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `session_id` (`session_id`)
				) ENGINE=MyISAM;");
                                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_myfavorites_session` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `session_hash_crc32` bigint(20) NOT NULL,
				  `session_hash` varchar(40) NOT NULL,
				  `mtime` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;"); 
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_delay_image_hit` (
				  `pid` int(11) NOT NULL,
				  `mtime` int(11) NOT NULL,
				  KEY `pid` (`pid`)
				) ENGINE=MyISAM;");
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_popular24` (
                  `pid` int(11) NOT NULL,
                  `hits` int(11) NOT NULL,
                  `added` int(11) NOT NULL,
                  PRIMARY KEY (`pid`),
                  KEY `hits` (`hits`,`pid`),
                  KEY `added` (`added`)
                ) ENGINE=MyISAM;");
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_rated24` (
                  `pid` int(11) NOT NULL,
                  `pic_rating` int(11) NOT NULL,
                  `votes` int(11) NOT NULL,
                  `added` int(11) NOT NULL,
                  PRIMARY KEY (`pid`),
                  KEY `pic_rating` (`pic_rating`,`votes`,`pid`),
                  KEY `added` (`added`)
                ) ENGINE=MyISAM;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_collection` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `time` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_image` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `pid` int(11) NOT NULL,
				  `duplicate_collection_id` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_image_hash` (
                  `pid` int(11) NOT NULL AUTO_INCREMENT,
                  `hash` varchar(40) NOT NULL,
                  PRIMARY KEY (`pid`),
                  KEY `hash` (`hash`)
                ) ENGINE=MyISAM;");
                
                // Create article module tables
                $db->query("CREATE TABLE IF NOT EXISTS `lh_article_static` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(200) NOT NULL,
				  `content` text NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;");
                
                $db->query("INSERT INTO `lh_article_static` (`id`, `name`, `content`) VALUES
				(1, 'Contact', '<p>\r\n	Contact information goes here</p>\r\n'),
				(2, 'Conditions', '<p>\r\n	Somes conditions goes here</p>\r\n'),
				(3, 'Gallery footer text', '<p>\r\n	&copy; 2010 <a href=\"lh:article/static/2\">Conditions</a> | <a href=\"lh:article/static/1\">Contact</a> | <a href=\"lh:feedback/form\">Feedback</a></p>\r\n');");

                // Create system configuration module tables
                $db->query("CREATE TABLE IF NOT EXISTS `lh_system_config` (
				  `identifier` varchar(50) NOT NULL,
				  `value` text NOT NULL,
				  `type` tinyint(1) NOT NULL DEFAULT '0',
				  `explain` varchar(250) NOT NULL,
				  `hidden` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`identifier`)
				) ENGINE=MyISAM;");
                
				$db->query("INSERT INTO `lh_system_config` (`identifier`, `value`, `type`, `explain`, `hidden`) VALUES
						('footer_article_id', 'a:3:{s:3:\"eng\";s:1:\"3\";s:3:\"lit\";s:2:\"28\";s:10:\"site_admin\";s:2:\"29\";}', 1, 'Footer article ID', 0),
						('max_photo_size', '5120', 0, 'Maximum photo size in kilobytes', 0),
						('max_archive_size', '20480', 0, 'Maximum archive size in kilobytes', 0),
						('file_queue_limit', '20', 0, 'How many files user can upload in single session', 0),
						('file_upload_limit', '200', 0, 'How many files upload during one session', 0),
						('thumbnail_width_x', '120', 0, 'Small thumbnail width - x', 0),
						('thumbnail_width_y', '130', 0, 'Small thumbnail width - Y', 0),
						('normal_thumbnail_width_x', '400', 0, 'Normal size thumbnail width - x', 0),
						('normal_thumbnail_width_y', '400', 0, 'Normal size thumbnail width - y', 0),
						('thumbnail_scale_algorithm', 'croppedThumbnail', 0, 'It can be \"scale\" or \"croppedThumbnail\" - makes perfect squares, or \"croppedThumbnailTop\" makes perfect squares, image cropped from top', 0),
						('google_analytics_token', '', 0, 'Google analytics API key', 0),
						('google_analytics_site_profile_id', '', 0, 'Google analytics site profile id', 0),
						('thumbnail_quality_default', '93', 0, 'Converted small thumbnail image quality', 0),
						('normal_thumbnail_quality', '93', 0, 'Converted normal thumbnail quality', 0),
						('watermark_data', 'a:9:{s:17:\"watermark_enabled\";b:0;s:21:\"watermark_enabled_all\";b:0;s:9:\"watermark\";s:0:\"\";s:6:\"size_x\";i:200;s:6:\"size_y\";i:50;s:18:\"watermark_disabled\";b:1;s:18:\"watermark_position\";s:12:\"bottom_right\";s:28:\"watermark_position_padding_x\";i:10;s:28:\"watermark_position_padding_y\";i:10;}', 0, 'Not shown public, editing is done in watermark module', 1),
						('full_image_quality', '93', 0, 'Full image quality', 0),
						('popularrecent_timeout', '24', 0, 'Most popular images timeout in hours', 0),
						('ratedrecent_timeout', '24', 0, 'Recently images timeout in hours', 0);");


				// Shop module
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_base_setting` (
				  `identifier` varchar(100) NOT NULL,
				  `value` varchar(100) NOT NULL,
				  `explain` varchar(100) NOT NULL,
				  PRIMARY KEY (`identifier`)
				) ENGINE=MyISAM;");
				
				$db->query("INSERT INTO `lh_shop_base_setting` (`identifier`, `value`, `explain`) VALUES
				('credit_price', '0.65', 'Credit price'),
				('max_downloads', '2', 'How many downloads can be done using download URL'),
				('main_currency', 'EUR', 'Shop base currency');");

				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_basket_image` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `session_id` int(11) NOT NULL,
				  `pid` int(11) NOT NULL,
				  `variation_id` int(11) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `session_id` (`session_id`)
				) ENGINE=MyISAM");
				
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_basket_session` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `session_hash_crc32` bigint(20) NOT NULL,
				  `session_hash` varchar(40) NOT NULL,
				  `mtime` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_image_variation` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `width` int(11) NOT NULL,
				  `height` int(11) NOT NULL,
				  `name` varchar(50) NOT NULL,
				  `credits` int(11) NOT NULL,
				  `position` int(11) NOT NULL DEFAULT '0',
				  `type` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
				
				
				$db->query("INSERT INTO `lh_shop_image_variation` (`id`, `width`, `height`, `name`, `credits`, `position`, `type`) VALUES
				(1, 800, 800, 'Small', 3, 20, 0),
				(3, 480, 480, 'Extra small', 1, 10, 0),
				(4, 1414, 1414, 'Medium', 4, 30, 0),
				(5, 1825, 1825, 'Large', 5, 40, 0),
				(6, 0, 0, 'Original', 11, 60, 1);");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_order` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_time` int(11) NOT NULL,
				  `user_id` int(11) NOT NULL,
				  `status` int(11) NOT NULL DEFAULT '0',
				  `basket_id` int(11) NOT NULL,
				  `email` varchar(100) NOT NULL,
				  `payment_gateway` varchar(100) NOT NULL,
				  `currency` varchar(3) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
				
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_order_item` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_id` int(11) NOT NULL,
				  `pid` int(11) NOT NULL,
				  `image_variation_id` int(11) NOT NULL,
				  `hash` varchar(40) NOT NULL,
				  `credit_price` decimal(10,4) NOT NULL,
				  `credits` int(11) NOT NULL,
				  `download_count` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_payment_setting` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `identifier` varchar(50) NOT NULL,
				  `param` varchar(50) NOT NULL,
				  `value` varchar(100) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `identifier` (`identifier`,`param`)
				) ENGINE=MyISAM ;");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_user_credit` (
				  `user_id` int(11) NOT NULL,
				  `credits` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`user_id`)
				) ENGINE=MyISAM;");
				
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_user_credit_order` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `credits` int(11) NOT NULL,
				  `status` int(11) NOT NULL,
				  `date` int(11) NOT NULL,
				  `payment_gateway` varchar(100) NOT NULL,
				  `currency` varchar(3) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;");
								
				$db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_searchhistory` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `keyword` varchar(100) NOT NULL,
                  `countresult` int(11) NOT NULL,
                  `last_search` int(11) NOT NULL,
                  `crc32` bigint(20) NOT NULL,
                  `searches_done` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `keyword_2` (`crc32`,`keyword`)
                ) ENGINE=MyISAM;");
					
                $db->query("CREATE VIEW `sphinxseearch` AS SELECT `lh_gallery_images`.`pid` AS `id`,`lh_gallery_images`.`pid` AS `pid`,`lh_gallery_images`.`hits` AS `hits`,`lh_gallery_images`.`title` AS `title`,`lh_gallery_images`.`mtime` AS `mtime`,`lh_gallery_images`.`keywords` AS `keywords`,`lh_gallery_images`.`caption` AS `caption`,`lh_gallery_images`.`comtime` AS `comtime`,`lh_gallery_images`.`rtime` AS `rtime`,`lh_gallery_images`.`pic_rating` AS `pic_rating`,`lh_gallery_images`.`votes` AS `votes`,replace(replace(`lh_gallery_images`.`filepath`,'/',' '),'-',' ') AS `file_path`,replace(replace(`lh_gallery_images`.`filename`,'-',' '),'_',' ') AS `filename`,`lh_gallery_albums`.`title` AS `album_title`,`lh_gallery_albums`.`keyword` AS `album_keyword`,`lh_gallery_albums`.`description` AS `album_description`,`lh_gallery_categorys`.`name` AS `category_name`,`lh_gallery_categorys`.`description` AS `category_description`,`lh_gallery_images`.`pwidth`,`lh_gallery_images`.`pheight`,concat(`lh_gallery_images`.`pwidth`,'x',`lh_gallery_images`.`pheight`) AS `pdimension` from ((`lh_gallery_images` left join `lh_gallery_albums` on((`lh_gallery_images`.`aid` = `lh_gallery_albums`.`aid`))) left join `lh_gallery_categorys` on((`lh_gallery_categorys`.`cid` = `lh_gallery_albums`.`category`))) where (`lh_gallery_images`.`approved` = 1);");
                                                 
                $RoleFunction = new erLhcoreClassModelRoleFunction();
                $RoleFunction->role_id = $Role->id;
                $RoleFunction->module = '*';
                $RoleFunction->function = '*';                
                erLhcoreClassRole::getSession()->save($RoleFunction);
                                
                $RoleFunctionRegistered = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegistered->role_id = $RoleRegistered->id;
                $RoleFunctionRegistered->module = 'lhuser';
                $RoleFunctionRegistered->function = 'selfedit';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegistered);
                
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhgallery';
                $RoleFunctionRegisteredGallery->function = 'use';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                                
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhgallery';
                $RoleFunctionRegisteredGallery->function = 'personal_albums';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                
                // Auto approvement for registered users           
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhgallery';
                $RoleFunctionRegisteredGallery->function = 'auto_approve';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                   
                
                $CategoryData = new erLhcoreClassModelGalleryCategory();
                $CategoryData->name = 'Users galleries';
                $CategoryData->hide_frontpage = 1;
                $CategoryData->owner_id = $UserData->id;
                erLhcoreClassGallery::getSession()->save($CategoryData); 
                 
                $cfgSite = erConfigClassLhConfig::getInstance();
	            $cfgSite->conf->setSetting( 'gallery_settings', 'default_gallery_category', $CategoryData->cid);	     
	            $cfgSite->conf->setSetting( 'site', 'installed', true);	     
	            $cfgSite->conf->setSetting( 'user_settings', 'default_user_group', $GroupDataRegistered->id);	     
	            $cfgSite->conf->setSetting( 'user_settings', 'anonymous_user_id', $UserDataAnonymous->id);	     
	            $cfgSite->save();
	           
    	        $tpl->setFile('lhinstall/install4.tpl.php');
    	       
            } else {      
                
               $tpl->set('admin_username',$form->AdminUsername);               
               if ( $form->hasValidData( 'AdminEmail' ) ) $tpl->set('admin_email',$form->AdminEmail);                      
    	       $tpl->set('admin_name',$form->AdminName);
    	       $tpl->set('admin_surname',$form->AdminSurname);	       
    	      	       
    	       $tpl->set('errors',$Errors);
    	            
    	       
    	       $tpl->setFile('lhinstall/install3.tpl.php');
            }
	    } else {
	        $tpl->setFile('lhinstall/install3.tpl.php');
	    }
	    	
	    break;
	    
	case '4':
	    $tpl->setFile('lhinstall/install4.tpl.php');
	    break;
	    
	default:
	    $tpl->setFile('lhinstall/install1.tpl.php');
		break;
}

$Result['content'] = $tpl->fetch();
$Result['pagelayout'] = 'install';
$Result['path'] = array(array('title' => 'High performance photo gallery install'));

} catch (Exception $e){
	echo "Make sure that &quot;cache/*&quot; is writable and then <a href=\"".erLhcoreClassDesign::baseurl('install/install')."\">try again</a>";
}
?>