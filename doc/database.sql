CREATE TABLE `post` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user` varchar(30) NOT NULL DEFAULT '' COMMENT 'user name',
 `content` text NOT NULL,
 `collection_id` int(11) NOT NULL DEFAULT '0' COMMENT '属于哪个合集',
 `ip` varchar(128) NOT NULL DEFAULT '0.0.0.0',
 `published` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

CREATE TABLE `discuss` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `post_id` int(11) NOT NULL DEFAULT '0',
 `reply_id` int(11) NOT NULL DEFAULT '0',
 `user` varchar(30) NOT NULL DEFAULT '' COMMENT '评论用户名',
 `content` text NOT NULL COMMENT '讨论内容',
 `ip` varchar(128) NOT NULL DEFAULT '0.0.0.0',
 `discuss_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`id`),
 KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

CREATE TABLE `collection` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(30) NOT NULL DEFAULT '' COMMENT '集合名称',
 `count` int(11) NOT NULL DEFAULT '0',
 `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`id`),
 UNIQUE KEY `collection_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

CREATE TABLE `history` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `collection` int(11) NOT NULL DEFAULT '0' COMMENT '合集数',
 `post` int(11) NOT NULL DEFAULT '0' COMMENT '帖子数',
 `discuss` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
 `date` date NOT NULL DEFAULT '0000-00-00' COMMENT '时间',
 PRIMARY KEY (`id`),
 UNIQUE KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;
