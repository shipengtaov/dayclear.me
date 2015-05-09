<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class ScriptHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();

		$this->notify_post_cookie_name = Config::get('app.cookie.notify_post');
		$this->notify_collection_cookie_name = Config::get('app.cookie.notify_collection');
		$this->publish_post_cookie_name = Config::get('app.cookie.publish_post');
		$this->notification_cookie_name = Config::get('app.cookie.notification');
		$this->max_post_cookie_name = Config::get('app.cookie.max_post');
		$this->username_cookie_name = Config::get('app.cookie.username');
	}

	public function get(){
		$cookie = [
			'publish' => [
				'name' => $this->publish_post_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->publish_post_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->publish_post_cookie_name) ?: '空值',
				'text' => '存储所有自己发表的帖子id',
			],
			'notify_post' => [
				'name' => $this->notify_post_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->notify_post_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->notify_post_cookie_name) ?: '空值',
				'text' => '存储所有需要提醒更新的帖子, 即 follow 的帖子',
			],
			'notify_collection' => [
				'name' => $this->notify_collection_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->notify_collection_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->notify_collection_cookie_name) ?: '空值',
				'text' => '存储所有需要提醒更新的合集, 即 follow 的合集',
			],
			'notification' => [
				'name' => $this->notification_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->notification_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->notification_cookie_name) ?: '空值',
				'text' => '存储所有更新提醒, 包括帖子和合集',
			],
			'max_post' => [
				'name' => $this->max_post_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->max_post_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->max_post_cookie_name) ?: '空值',
				'text' => '最新发布的帖子的时间戳',
			],
			'username' => [
				'name' => $this->username_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->username_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->username_cookie_name) ?: '空值',
				'text' => '保存的用户名',
			],
			'PHPSESSID' => [
				'name' => 'PHPSESSID',
				'origin_value' => htmlspecialchars(Cookie::getRaw('PHPSESSID') ?: '空值'),
				'format_value' => htmlspecialchars(Cookie::getRaw('PHPSESSID') ?: '空值'),
				'text' => 'PHP Session 存储的随机id',
			],
		];
		echo $this->view->make('script.php')->with([
			'title' => '脚本 - ' . Config::get('app.title'),
			'cookie' => $cookie,
		]);
	}
}

$methods = ['get'];
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (!in_array($method, $methods)){
	exit("不支持 {$method}");
}

$scriptHandler = new ScriptHandler();
$scriptHandler->$method();
