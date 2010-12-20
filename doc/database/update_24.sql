CREATE TABLE IF NOT EXISTS `lh_gallery_sphinx_search` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `caption` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mtime` int(11) NOT NULL,
  `comtime` int(11) NOT NULL,
  `rtime` int(11) NOT NULL,
  `pic_rating` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `pwidth` smallint(6) NOT NULL,
  `pheight` smallint(6) NOT NULL,
  `colors` text NOT NULL,
  `text_index` text NOT NULL,
  `hits` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE  `lh_gallery_pallete_images_stats` (
`pid` INT NOT NULL ,
`colors` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY (  `pid` )
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS `lh_gallery_last_index` (
  `identifier` varchar(50) NOT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `lh_gallery_last_index` (`identifier`, `value`) VALUES
('image_index', 0),
('sphinx_index', 0);

DROP VIEW `sphinxseearch`;
                
ALTER TABLE  `lh_gallery_pallete_images` CHANGE  `pallete_id`  `pallete_id` MEDIUMINT NOT NULL;
ALTER TABLE  `lh_gallery_pallete_images` CHANGE  `count`  `count` MEDIUMINT NOT NULL;