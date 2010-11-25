ALTER TABLE  `lh_gallery_images` DROP INDEX  `aid_4` ,
ADD INDEX  `aid_4` (  `aid` ,  `approved` ,  `pwidth` ,  `pheight` ,  `comtime` ,  `pid` );