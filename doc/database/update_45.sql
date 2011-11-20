ALTER TABLE  `lh_gallery_myfavorites_session` DROP  `session_hash_crc32`;
ALTER TABLE  `lh_gallery_myfavorites_session` ADD INDEX (  `user_id` );
ALTER TABLE  `lh_gallery_myfavorites_session` ADD INDEX (  `session_hash` );