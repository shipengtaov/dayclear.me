<?php

return [
	"environment" => [
		"local" => "shipengtaodeMacBook-Pro.local",
	],

	"domain" => "http://dayclear.me",

	"title" => "DayClear",
	/* ------------------------------------------------------------------------
	 | 每个 ip 每天最大数量
	 * ------------------------------------------------------------------------
	 */
	"daily_limit" => 50,

	/* ------------------------------------------------------------------------
	 | 长度限制
	 | username : 用户名最大字数
	 | collection : 合集最大字数
	 | post : post 最大字数
	 | discuss : discuss 最大字数
	 | reply : reply 回复最大字数
	 * ------------------------------------------------------------------------
	 */
	"length_limit" => [
		"username" => 20,
		"collection" => 20,
		"post" => 500,
		"discuss" => 500,
		"reply" => 500,
	],

	"pattern" => [
		"collection" => [
			"deny" => "#[\s/\?&]#i",
			"text" => "合集名称不能包含: 空格, /, ?, &",
		],
	],

	/* ------------------------------------------------------------------------
	 | 默认用户名
	 * ------------------------------------------------------------------------
	 */
	 "default_username" => "Annoymous",

	/* ------------------------------------------------------------------------
	 | 存储的 cookie
	 |    publish_post : 自己发布的 post
	 |    notify_post  : 所有需要提醒的 post, 保存时间戳用于提醒更新
	 |    notify_collection : 关注的合集, 保存时间戳用于提醒更新
	 |    notification : 通知
	 |    max_post : 当前最新的 post_id
	 |    username : 保存的用户名
	 * ------------------------------------------------------------------------
	 */
	 "cookie" => [
	 	"publish_post" => "publish_post",
	 	"notify_post" => "notify_post",
	 	"notify_collection" => "notify_collection",
	 	"notification" => "notification",
	 	"max_post" => "max_post",
	 	"username" => "username",
	 ],

	 "session" => [
	 	"admin" => "admin",
	 ],

	 "auth" => [
	 	"admin" => [
	 		"username" => "admin",
	 		"password" => "password",
	 	],
	 ],
];
