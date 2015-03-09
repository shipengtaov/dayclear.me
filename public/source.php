<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class SourceHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->notify_post_cookie_name = Config::get('app.cookie.notify_post');
		$this->publish_post_cookie_name = Config::get('app.cookie.publish_post');
		$this->notification_cookie_name = Config::get('app.cookie.notification');
		$this->max_post_cookie_name = Config::get('app.cookie.max_post');
		$this->username_cookie_name = Config::get('app.cookie.username');
	}

	public function get(){
		$cookie = [
			'notify_post' => [
				'name' => $this->notify_post_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->notify_post_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->notify_post_cookie_name) ?: '空值',
				'text' => '存储所有需要提醒更新的帖子, 包括所有自己发表的和参与讨论的, json格式, 转换为数组后, key为帖子id, value为最后更新的时间戳',
			],
			'publish' => [
				'name' => $this->publish_post_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->publish_post_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->publish_post_cookie_name) ?: '空值',
				'text' => '存储所有自己发表的帖子id',
			],
			'notification' => [
				'name' => $this->notification_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->notification_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->notification_cookie_name) ?: '空值',
				'text' => '存储所有更新提醒, 包括更新的帖子id, 帖子更新的数量, 更新的最小评论id',
			],
			'max_post' => [
				'name' => $this->max_post_cookie_name,
				'origin_value' => htmlspecialchars(Cookie::getRaw($this->max_post_cookie_name) ?: '空值'),
				'format_value' => Cookie::get($this->max_post_cookie_name) ?: '空值',
				'text' => '已查看的最新的帖子id',
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
		echo $this->view->make('source.php')->with([
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

$sourceHandler = new SourceHandler();
$sourceHandler->$method();
