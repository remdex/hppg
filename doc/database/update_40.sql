ALTER TABLE  `lh_gallery_albums` ADD  `hidden` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `lh_gallery_albums` DROP INDEX  `alb_category` ,ADD INDEX  `alb_category` (  `category` ,  `hidden` );
ALTER TABLE  `lh_gallery_albums` DROP INDEX  `alb_category` ,ADD INDEX  `alb_category` (  `category` ,  `hidden` ,  `pos` ,  `aid` );
ALTER TABLE  `lh_gallery_albums` DROP INDEX  `aid` ,ADD INDEX  `aid` (  `category` ,  `pos` ,  `aid` );
ALTER TABLE  `lh_gallery_searchhistory` ADD INDEX (  `last_search` );