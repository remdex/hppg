CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_image_hash` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(40) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM;