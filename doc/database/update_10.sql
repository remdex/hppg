CREATE TABLE IF NOT EXISTS `lh_gallery_popular24` (
  `pid` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `hits` (`hits`,`pid`),
  KEY `added` (`added`)
) ENGINE=MyISAM;