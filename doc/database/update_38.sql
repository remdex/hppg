INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'forum_photo_width',  '500',  '0',  'Forum photo width',  '0'
), (
'forum_photo_height',  '500',  '0',  'Forum photo height',  '0'
), (
'posts_per_page',  '20',  '0',  'How many post messages show per page',  '0'
), (
'minimum_post_to_hot',  '20',  '0',  'How many post to became hot topic',  '0'
);

CREATE TABLE IF NOT EXISTS `lh_forum_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `name` varchar(50) NOT NULL,
  `placement` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `topic_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Static counter for performance',
  `message_count` int(11) NOT NULL DEFAULT '0',
  `last_topic_id` int(11) NOT NULL DEFAULT '0' COMMENT 'For performance we store last topic id with message',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `id` (`placement`,`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lh_forum_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `file_path` varchar(250) NOT NULL,
  `file_size` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lh_forum_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lh_forum_message_delta` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lh_forum_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `msg_id` (`msg_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lh_forum_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) NOT NULL DEFAULT '0',
  `topic_name` varchar(200) NOT NULL,
  `path_1` int(11) NOT NULL DEFAULT '0',
  `path_2` int(11) NOT NULL,
  `path_3` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `path_0` int(11) NOT NULL DEFAULT '0',
  `message_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Static counter for performance',
  `last_message_ctime` int(11) NOT NULL DEFAULT '0',
  `topic_status` tinyint(1) NOT NULL DEFAULT '0',
  `main_category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `path_1` (`path_1`,`last_message_ctime`),
  KEY `path_3` (`path_3`,`last_message_ctime`),
  KEY `path_2` (`path_2`,`last_message_ctime`),
  KEY `path_0` (`path_0`,`last_message_ctime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;