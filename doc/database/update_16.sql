INSERT INTO  `lh_system_config` (
`identifier` ,
`value` ,
`type` ,
`explain` ,
`hidden`
)
VALUES (
'flash_screenshot_command',  'bin/shell/xvfb-run.sh --server-args="-screen 0, 1024x2730x24" bin/shell/screenshot.sh',  '0',  'Command witch is executed for conversion flash',  '0'
);

CREATE TABLE IF NOT EXISTS `lh_gallery_pending_convert` (
  `pid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM;