INSERT INTO  `lh_gallery_filetypes` (
`extension` ,
`mime` ,
`content` ,
`player`
)
VALUES (
'mpg',  'video/mpeg',  'movie',  'VIDEO'
), (
'mpeg',  'video/mpeg',  'movie',  'VIDEO'
), (
'avi',  'video/x-msvideo',  'movie',  'VIDEO'
);

INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'video_convert_command',  'ffmpeg -y -i {original_file} -qmax 15 -s 580x440 -ar 22050 -ab 32 -f flv {converted_file} &> /dev/null',  '0',  '',  '0'
);

UPDATE  `lh_system_config` SET  `value` =  '*.jpg;*.gif;*.png;*.png;*.bmp;*.ogv;*.swf;*.flv;*.mpeg;*.avi;*.mpg' WHERE `lh_system_config`.`identifier` = 'allowed_file_types' LIMIT 1 ;