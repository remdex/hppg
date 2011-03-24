CREATE TABLE IF NOT EXISTS `lh_gallery_images_rate_ban_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(39) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
);

CREATE TABLE IF NOT EXISTS `lh_gallery_images_rate_last_ip` (
  `pid` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  PRIMARY KEY (`pid`)
);

CREATE TABLE IF NOT EXISTS `lh_gallery_images_comment_ban_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(39) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
);

INSERT INTO  `lh_gallery_filetypes` (
`extension` ,
`mime` ,
`content` ,
`player`
)
VALUES (
'mp4',  'video/mp4',  'movie',  'VIDEO'
);