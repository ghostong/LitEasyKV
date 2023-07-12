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
   `weight` int(11) NOT NULL DEFAULT '1' COMMENT '自定义排序',
   UNIQUE KEY `unique_id` (`topic_id`,`key_id`,`value_id`) USING BTREE,
   KEY `topic_id` (`topic_id`,`key_id`,`value_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;