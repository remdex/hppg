ALTER TABLE  `lh_gallery_sphinx_search` ADD  `text_index_length` MEDIUMINT NOT NULL ;
UPDATE `lh_gallery_sphinx_search` SET `text_index_length` = char_length(`lh_gallery_sphinx_search`.`colors`);