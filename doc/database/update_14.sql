INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'ratedrecent_timeout',  '24',  '0',  'Top rated images in 24 timout',  '0'
);

CREATE TABLE IF NOT EXISTS `lh_gallery_rated24` (
  `pid` int(11) NOT NULL,
  `pic_rating` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `sort_rated` (`pic_rating`,`votes`,`pid`),
  KEY `added` (`added`)
) ENGINE=MyISAM; 