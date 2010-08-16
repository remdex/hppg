CREATE TABLE IF NOT EXISTS `lh_gallery_searchhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL,
  `countresult` int(11) NOT NULL,
  `last_search` int(11) NOT NULL,
  `crc32` bigint(20) NOT NULL,
  `searches_done` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `keyword_2` (`crc32`,`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;