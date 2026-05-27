CREATE TABLE `easy_kv` (
   `topic_id` char(8) NOT NULL COMMENT 'topic的MD5 8位片段',
   `key_id` char(8) NOT NULL COMMENT 'key的MD5 8位片段',
   `value_id` char(8) NOT NULL COMMENT 'value的MD5 8位片段',
   `topic` varchar(64) NOT NULL,
   `key` varchar(64) NOT NULL,
   `value` varchar(1024) NOT NULL,
   `extend` text NOT NULL COMMENT '扩展字段',
   `create_time` datetime NOT NULL COMMENT '创建时间',
   `update_time` datetime NOT NULL COMMENT '更新时间',
   `weight` int unsigned NOT NULL DEFAULT '1' COMMENT '自定义排序',
    PRIMARY KEY (`topic_id`,`key_id`,`value_id`),
    KEY `idx_topic_key_weight_value` (`topic_id`,`key_id`,`weight`,`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;