ALTER TABLE  `lh_gallery_images` CHANGE  `sort_rated`  `has_preview` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  'Used for video files';
ALTER TABLE  `lh_gallery_images` ADD  `media_type` TINYINT NOT NULL ;

CREATE TABLE IF NOT EXISTS `lh_gallery_filetypes` (
  `extension` char(7) NOT NULL DEFAULT '',
  `mime` char(254) DEFAULT NULL,
  `content` char(15) DEFAULT NULL,
  `player` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`extension`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `lh_gallery_filetypes` (`extension`, `mime`, `content`, `player`) VALUES
('jpg', 'image/jpg', 'image', 'IMAGE'),
('jpeg', 'image/jpeg', 'image', 'IMAGE'),
('jpe', 'image/jpe', 'image', 'IMAGE'),
('gif', 'image/gif', 'image', 'IMAGE'),
('png', 'image/png', 'image', 'IMAGE'),
('bmp', 'image/bmp', 'image', 'IMAGE'),
('jpc', 'image/jpc', 'image', 'IMAGE'),
('jp2', 'image/jp2', 'image', 'IMAGE'),
('jpx', 'image/jpx', 'image', 'IMAGE'),
('jb2', 'image/jb2', 'image', 'IMAGE'),
('swc', 'image/swc', 'image', 'IMAGE'),
('iff', 'image/iff', 'image', 'IMAGE'),
('psd', 'image/psd', 'image', 'IMAGE'),
('ogg', 'audio/ogg', 'audio', 'HTMLA'),
('oga', 'audio/ogg', 'audio', 'HTMLA'),
('ogv', 'video/ogg', 'movie', 'HTMLV'),
('swf', 'application/x-shockwave-flash', 'movie', 'SWF'),
('flv', 'video/x-flv', 'movie', 'FLV')
;

INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'loop_video',  '0',  '0',  'Should HTML5 video be looped? (1 - yes,0 - no))',  '0'
);

INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'allowed_file_types',  '*.jpg;*.gif;*.png;*.png;*.bmp;*.ogv;*.swf',  '0',  'List of allowed file types to upload',  '0'
);