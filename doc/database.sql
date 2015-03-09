DROP TABLE IF EXISTS `post`;
CREATE TABLE `post`(
	`id` int(11) NOT NULL auto_increment PRIMARY KEY,
	`user` varchar(30) NOT NULL DEFAULT '' COMMENT 'user name',
	`content` text NOT NULL DEFAULT '',
	`ip` varchar(128) NOT NULL DEFAULT '0.0.0.0',
	`published` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=10000 CHARSET=utf8;

DROP TABLE IF EXISTS `discuss`;
CREATE TABLE `discuss`(
	`id` int(11) NOT NULL auto_increment,
	`post_id` int(11) NOT NULL DEFAULT 0,
	`reply_id` int(11) not null default 0,
	`user` varchar(30) NOT NULL DEFAULT '' COMMENT '评论用户名',
	`content` text NOT NULL DEFAULT '' COMMENT '讨论内容',
	`ip` varchar(128) NOT NULL DEFAULT '0.0.0.0',
	`discuss_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY(`id`),
	INDEX (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 CHARSET=utf8;

CREATE TABLE `history`(
	`id` int(11) NOT NULL auto_increment,
	`post` int(11) NOT NULL DEFAULT 0 COMMENT "帖子数",
	`discuss` int(11) NOT NULL DEFAULT 0 COMMENT "评论数",
	`date` date NOT NULL DEFAULT '0000-00-00' COMMENT "时间",
	PRIMARY KEY(`id`),
	UNIQUE `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 CHARSET=utf8;
