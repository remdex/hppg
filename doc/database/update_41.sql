CREATE TABLE IF NOT EXISTS `lh_user_fb` (
  `user_id` int(11) NOT NULL,
  `fb_user_id` bigint(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `link` varchar(250) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fb_user_id` (`fb_user_id`)
) DEFAULT CHARSET=utf8;