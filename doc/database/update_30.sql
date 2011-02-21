INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'google_translate_api_key',  '',  '0',  'Google translate API key, can be obtained from https://code.google.com/apis/console/',  '0'
);
ALTER TABLE  `lh_gallery_comments` ADD  `lang` VARCHAR( 5 ) NOT NULL ;