CREATE 

VIEW `lh_gallery_sphinx_search_view` AS select 

`lh_gallery_sphinx_search`.`id` AS `id`,
`lh_gallery_albums`.`title` AS `album_title`,
`lh_gallery_albums`.`description` AS `album_description`,
`lh_gallery_albums`.`keyword` AS `album_keyword`,
`lh_gallery_albums`.`aid` AS `album_id`,
`lh_gallery_categorys`.`name` AS `category_name`,
`lh_gallery_categorys`.`description` AS `category_description`,
`lh_gallery_sphinx_search`.`title` AS `title`,
`lh_gallery_sphinx_search`.`caption` AS `caption`,
`lh_gallery_sphinx_search`.`filename` AS `filename`,
`lh_gallery_sphinx_search`.`file_path` AS `file_path`,
`lh_gallery_sphinx_search`.`mtime` AS `mtime`,
`lh_gallery_sphinx_search`.`comtime` AS `comtime`,
`lh_gallery_sphinx_search`.`rtime` AS `rtime`,
`lh_gallery_sphinx_search`.`pic_rating` AS `pic_rating`,
`lh_gallery_sphinx_search`.`votes` AS `votes`,
`lh_gallery_sphinx_search`.`pwidth` AS `pwidth`,
`lh_gallery_sphinx_search`.`pheight` AS `pheight`,
`lh_gallery_sphinx_search`.`colors` AS `colors`,
`lh_gallery_sphinx_search`.`text_index` AS `text_index`,
`lh_gallery_sphinx_search`.`hits` AS `hits`,
`lh_gallery_sphinx_search`.`pid` AS `pid`,
'imgan' AS `fake_keyword`
 
from 

`lh_gallery_sphinx_search`

INNER JOIN `lh_gallery_images` ON `lh_gallery_images`.`pid` = `lh_gallery_sphinx_search`.`id`
INNER JOIN `lh_gallery_albums` ON `lh_gallery_albums`.`aid` = `lh_gallery_images`.`aid`
INNER JOIN `lh_gallery_categorys` ON `lh_gallery_albums`.`category` = `lh_gallery_categorys`.`cid`;