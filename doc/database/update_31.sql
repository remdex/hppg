CREATE TABLE IF NOT EXISTS `lh_oid_associations` (
  `server_url` blob NOT NULL,
  `handle` varchar(255) NOT NULL,
  `secret` blob NOT NULL,
  `issued` int(11) NOT NULL,
  `lifetime` int(11) NOT NULL,
  `assoc_type` varchar(64) NOT NULL,
  PRIMARY KEY (`server_url`(255),`handle`)
);

CREATE TABLE IF NOT EXISTS `lh_oid_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` blob NOT NULL,
  `user_id` int(11) NOT NULL,
  `open_id_type` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
);

CREATE TABLE IF NOT EXISTS `lh_oid_nonces` (
  `server_url` varchar(2047) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `salt` char(40) NOT NULL,
  UNIQUE KEY `server_url` (`server_url`(255),`timestamp`,`salt`)
);