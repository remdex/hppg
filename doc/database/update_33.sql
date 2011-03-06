INSERT INTO  `lh_gallery_filetypes` (
`extension` ,
`mime` ,
`content` ,
`player`
)
VALUES (
'wmv',  'video/x-ms-wmv',  'movie',  'VIDEO'
);
UPDATE  `lh_system_config` SET  `value` =  '*.jpg;*.gif;*.png;*.png;*.bmp;*.ogv;*.swf;*.flv;*.mpeg;*.avi;*.mpg;*.wmv' WHERE CONVERT(  `lh_system_config`.`identifier` USING utf8 ) =  'allowed_file_types' LIMIT 1 ;