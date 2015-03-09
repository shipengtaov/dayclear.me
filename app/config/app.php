<?php

return [
	"domain" => "http://daily-post.localhost",

	"title" => "Daily Post",
	/* ------------------------------------------------------------------------
	 | 每个 ip 每天最大数量
	 * ------------------------------------------------------------------------
	 */
	"daily_limit" => 50,

	/* ------------------------------------------------------------------------
	 | 长度限制
	 | post : post 最大字数
	 | discuss : discuss 最大字数
	 | reply : reply 回复最大字数
	 * ------------------------------------------------------------------------
	 */
	"length_limit" => [
		"username" => 20,
		"post" => 500,
		"discuss" => 500,
		"reply" => 500,
	],

	/* ------------------------------------------------------------------------
	 | 默认用户名
	 * ------------------------------------------------------------------------
	 */
	 "default_username" => "Annoymous",

	/* ------------------------------------------------------------------------
	 | 存储的 cookie
	 |    publish : 自己发布的 post
	 |    notify  : 所有需要提醒的 post
	 |    notification : 通知
	 |    max_post : 当前最新的 post_id
	 * ------------------------------------------------------------------------
	 */
	 "cookie" => [
	 	"publish_post" => "publish_post",
	 	"notify_post" => "notify_post",
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
