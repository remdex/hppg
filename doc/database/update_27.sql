ALTER TABLE  `lh_gallery_albums` ADD  `addtime` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `lh_gallery_albums` ADD INDEX (  `addtime` );
UPDATE lh_gallery_albums SET addtime = (SELECT MAX(ctime) FROM lh_gallery_images WHERE aid = lh_gallery_albums .aid AND approved = 1);