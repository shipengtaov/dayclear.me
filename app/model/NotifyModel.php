<?php

class NotifyModel{

	public $publish_cookie_name;
	public $notify_cookie_name;
	public $notification_cookie_name;

	public function __construct(){
		$this->publish_cookie_name = Config::get('app.cookie.publish_post');
		$this->notify_cookie_name = Config::get('app.cookie.notify_post');
		$this->notification_cookie_name = Config::get('app.cookie.notification');
	}

	/**
	 * 添加一条发布的 post_id
	 */
	public function addPublishPost($post_id){
		$cookie_name = $this->publish_cookie_name;
		$publish = Cookie::get($cookie_name, []);
		settype($publish, 'array');

		$publish = array_merge($publish, [$post_id]);
		Cookie::set($cookie_name, $publish);
		$this->addNotifyPost($post_id, time());
	}

	/**
	 * 添加或更新一条需要提醒的 post_id
	 */
	public function addNotifyPost($post_id, $timestamp=null){
		if (is_null($timestamp))
			$timestamp = time();

		$notify = Cookie::get($this->notify_cookie_name, []);
		settype($notify, 'array');

		$notify[$post_id] = $timestamp;
		Cookie::set($this->notify_cookie_name, $notify);
	}

	/**
	 * 清除一个关注的 post
	 */
	public function deleteNotifyPost($post_id){
		$notify = Cookie::get($this->notify_cookie_name, []);
		settype($notify, 'array');

		if (isset($notify[$post_id])){
			unset($notify[$post_id]);
			Cookie::set($this->notify_cookie_name, $notify);
		}
	}

	/**
	 * 判断是否已关注一个 post
	 */
	public function hasFollowPost($post_id){
		$notify = Cookie::get($this->notify_cookie_name, []);
		settype($notify, 'array');

		if (isset($notify[$post_id]) && !empty($notify[$post_id]))
			return true;
		else
			return false;
	}

	/**
	 * 获取所有关注的 post
	 */
	public function getAllFollowPost(){
		$notify = Cookie::get($this->notify_cookie_name, []);
		settype($notify, 'array');

		return $notify;
	}

	/**
	 * 更新一条提醒
	 */
	public function updateNotifyPost($post_id, $timestamp=null){
		if (is_null($timestamp))
			$timestamp = time();

		$notify = Cookie::get($this->notify_cookie_name, []);
		settype($notify, 'array');

		if (isset($notify[$post_id])){
			$notify[$post_id] = $timestamp;
			Cookie::set($this->notify_cookie_name, $notify);
		}
	}

	/**
	 * 更新一个cookie值
	 */
	public function update($name, $value){
		if ($name == $this->notify_cookie_name){
			Cookie::set($this->notify_cookie_name, $value);
		}
	}

	/**
	 * 添加或更新一条提醒
	 */
	public function addNotification($post_id){
		$notification = Cookie::get($this->notification_cookie_name, []);
		settype($notification, 'array');
		$notification = array_merge($notification, [$post_id]);
		Cookie::set($this->notification_cookie_name, $notification);
	}

	/**
	 * 将一条提醒标记为已读
	 */
	public function markReadOne($post_id){
		$notification = Cookie::get($this->notification_cookie_name, []);
		settype($notification, 'array');
		if (isset($notification[$post_id])){
			unset($notification[$post_id]);
			Cookie::set($this->notification_cookie_name, $notification);
		}
		// foreach ($notification as $key => $v) {
		// 	if ($v == $post_id){
		// 		unset($notification[$key]);
		// 		Cookie::set($this->notification_cookie_name, $notification);
		// 		break;
		// 	}
		// }
	}

	/**
	 * 将所有提醒标为已读
	 */
	public function markReadAll(){
		Cookie::clear($this->notification_cookie_name);

		$notify_cookie = Cookie::get($this->notify_cookie_name, []);
		settype($notify_cookie, 'array');
		foreach ($notify_cookie as &$timestamp) {
			$timestamp = time();
		}
		Cookie::set($this->notify_cookie_name, $notify_cookie);
	}
}
