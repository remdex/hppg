ALTER TABLE  `lh_gallery_pallete` ADD  `position` INT NOT NULL ;
UPDATE lh_gallery_pallete SET position = id * 20;
ALTER TABLE  `lh_gallery_pallete` ADD INDEX (  `position` );
UPDATE lh_gallery_pallete SET position = position - 20 WHERE id >= 12 AND id <= 20;
UPDATE lh_gallery_pallete SET position = 400 WHERE id = 11;
ALTER TABLE  `lh_gallery_pallete` CHANGE  `position`  `position` INT( 11 ) NOT NULL DEFAULT  '0';
DELETE FROM `lh_gallery_pallete_images` WHERE `count` < 25;