ALTER TABLE  `lh_gallery_pallete_images` DROP PRIMARY KEY ,ADD PRIMARY KEY (  `pallete_id` ,  `pid` );
ALTER TABLE `lh_gallery_pallete_images` ADD INDEX(`pid`);

ALTER TABLE  `lh_gallery_pallete_images` CHANGE  `pallete_id`  `pallete_id` SMALLINT( 3 ) NOT NULL;
ALTER TABLE  `lh_gallery_pallete_images` CHANGE  `count`  `count` SMALLINT( 5 ) NOT NULL;