ALTER TABLE  `lh_forum_topic` DROP INDEX  `path_2` ,
ADD INDEX  `path_2` (  `path_2` ,  `last_message_ctime` ,  `id` );

ALTER TABLE  `lh_forum_topic` DROP INDEX  `path_3` ,
ADD INDEX  `path_3` (  `path_3` ,  `last_message_ctime` ,  `id` );

ALTER TABLE  `lh_forum_topic` DROP INDEX  `path_1` ,
ADD INDEX  `path_1` (  `path_1` ,  `last_message_ctime` ,  `id` );

ALTER TABLE  `lh_forum_topic` DROP INDEX  `path_0` ,
ADD INDEX  `path_0` (  `path_0` ,  `last_message_ctime` ,  `id` );