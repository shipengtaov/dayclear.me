<?php

class NotifyModel{

	public $publish_cookie_name;
	public $notify_post_cookie_name;
	public $notification_cookie_name;

	public function __construct(){
		$this->publish_cookie_name = Config::get('app.cookie.publish_post');
		$this->notify_post_cookie_name = Config::get('app.cookie.notify_post');
		$this->notify_collection_cookie_name = Config::get('app.cookie.notify_collection');
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

		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		$notify_post[$post_id] = $timestamp;
		Cookie::set($this->notify_post_cookie_name, $notify_post);
	}

	/**
	 * 添加或更新一条需要提醒的 collection
	 */
	public function addNotifyCollection($collection_id, $timestamp=null){
		if (is_null($timestamp))
			$timestamp = time();

		$notify_collections = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collections, 'array');

		$notify_collections[$collection_id] = $timestamp;
		Cookie::set($this->notify_collection_cookie_name, $notify_collections);
	}

	/**
	 * 清除一个关注的 post
	 */
	public function deleteNotifyPost($post_id){
		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		if (isset($notify_post[$post_id])){
			unset($notify_post[$post_id]);
			Cookie::set($this->notify_post_cookie_name, $notify_post);
		}
	}

	/**
	 * 清除一个关注的 collection
	 */
	public function deleteNotifyCollection($collection_id){
		$notify_collections = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collections, 'array');

		if (isset($notify_collections[$collection_id])){
			unset($notify_collections[$collection_id]);
			Cookie::set($this->notify_collection_cookie_name, $notify_collections);
		}
	}

	/**
	 * 判断是否已关注一个 post
	 */
	public function hasFollowPost($post_id){
		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		if (isset($notify_post[$post_id]) && !empty($notify_post[$post_id]))
			return true;
		else
			return false;
	}

	/**
	 * 判断是否已关注一个 collection
	 */
	public function hasFollowCollection($collection_id){
		$notify_collections = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collections, 'array');

		if (isset($notify_collections[$collection_id]) && !empty($notify_collections[$collection_id]))
			return true;
		else
			return false;
	}

	/**
	 * 获取所有关注的 post
	 */
	public function getAllFollowPost(){
		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		return $notify_post;
	}

	/**
	 * 获取所有关注的 collection
	 */
	public function getAllFollowCollection(){
		$notify_collections = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collections, 'array');

		return $notify_collections;
	}

	/**
	 * 更新一条 post 提醒
	 */
	public function updateNotifyPost($post_id, $timestamp=null){
		if (is_null($timestamp))
			$timestamp = time();

		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		if (isset($notify_post[$post_id])){
			$notify_post[$post_id] = $timestamp;
			Cookie::set($this->notify_post_cookie_name, $notify_post);
		}
	}

	/**
	 * 更新一条 collection 提醒
	 */
	public function updateNotifyCollection($collection_id, $timestamp=null){
		if (is_null($timestamp))
			$timestamp = time();

		$notify_collections = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collections, 'array');

		if (isset($notify_collections[$collection_id])){
			$notify_collections[$collection_id] = $timestamp;
			Cookie::set($this->notify_collection_cookie_name, $notify_collections);
		}
	}

	/**
	 * 更新某一个 cookie 值, 用于清空无效信息时使用
	 */
	public function update($name, $value){
		Cookie::set($name, $value);
	}

	/**
	 * 将一条提醒标记为已读
	 */
	public function markReadOne($id, $type='post'){
		$notification = Cookie::get($this->notification_cookie_name, []);
		settype($notification, 'array');

		if ($type == 'post'){
			if (isset($notification['post'][$id])){
				unset($notification['post'][$id]);
				Cookie::set($this->notification_cookie_name, $notification);
			}
		} else if ($type == 'collection'){
			if (isset($notification['collection'][$id])){
				unset($notification['collection'][$id]);
				Cookie::set($this->notification_cookie_name, $notification);
			}
		}
	}

	/**
	 * 将所有提醒标为已读
	 */
	public function markReadAll(){
		Cookie::clear($this->notification_cookie_name);

		// 更新所有关注的 post 的时间戳
		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');
		foreach ($notify_post as &$timestamp) {
			$timestamp = time();
		}
		Cookie::set($this->notify_post_cookie_name, $notify_post);

		// 更新所有关注的 collection 的时间戳
		$notify_collection = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collection, 'array');
		foreach ($notify_collection as &$timestamp) {
			$timestamp = time();
		}
		Cookie::set($this->notify_collection_cookie_name, $notify_collection);
	}
}
