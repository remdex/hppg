CREATE VIEW `lh_forum_sphinx` AS SELECT 
`lh_forum_message`.`id` AS `id`,
`lh_forum_message`.`content` AS `content`,
`lh_forum_topic`.`topic_name` AS `topic_name`,
`lh_forum_topic`.`id` AS `topic_id` from (`lh_forum_message` join `lh_forum_topic` on((`lh_forum_topic`.`id` = `lh_forum_message`.`topic_id`)));