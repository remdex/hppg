CREATE TABLE IF NOT EXISTS `lh_users_profile` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`name` varchar(150) NOT NULL,
`surname` varchar(150) NOT NULL,
`intro` text NOT NULL,
`photo` varchar(100) NOT NULL,
`variations` text NOT NULL,
`filepath` varchar(200) NOT NULL,
`website` varchar(200) NOT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`)
) DEFAULT CHARSET=utf8;