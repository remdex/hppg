INSERT INTO `lh_gallery_last_index` (`identifier`, `value`) VALUES('face_index', 0);

CREATE TABLE IF NOT EXISTS `lh_gallery_face_data` (
  `pid` int(11) NOT NULL,
  `data` text NOT NULL,
  `sphinx_data` varchar(255) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;