CREATE TABLE IF NOT EXISTS `lh_gallery_shard_limit` (
                  `pid` int(11) NOT NULL DEFAULT '0',
                  `offset` int(11) NOT NULL DEFAULT '0',
                  `sort` varchar(40) NOT NULL,
                  `filter` varchar(40) NOT NULL,
                  `identifier` varchar(50) NOT NULL,
                  PRIMARY KEY (`offset`,`sort`,`filter`,`identifier`),
                  KEY `identifier` (`identifier`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;