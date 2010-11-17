ALTER TABLE  `lh_gallery_searchhistory` DROP  `id`;
ALTER TABLE  `lh_gallery_searchhistory` DROP  `crc32`;
ALTER TABLE  `lh_gallery_searchhistory` DROP INDEX  `keyword_2`;
ALTER TABLE  `lh_gallery_searchhistory` DROP INDEX  `keyword`;
ALTER TABLE  `lh_gallery_searchhistory` ADD PRIMARY KEY (  `keyword` );