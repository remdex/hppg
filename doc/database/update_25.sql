ALTER TABLE  `lh_gallery_pallete_images` DROP PRIMARY KEY ,ADD PRIMARY KEY (  `pallete_id` ,  `pid` );
ALTER TABLE `lh_gallery_pallete_images` ADD INDEX(`pid`);