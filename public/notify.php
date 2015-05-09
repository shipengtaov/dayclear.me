<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class NotifyHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->collection_model = new CollectionModel();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->notify_model = new NotifyModel();

		$this->notify_post_cookie_name = Config::get('app.cookie.notify_post');
		$this->notify_collection_cookie_name = Config::get('app.cookie.notify_collection');
		$this->notification_cookie_name = Config::get('app.cookie.notification');
		$this->max_post_cookie_name = Config::get('app.cookie.max_post');
	}

	public function get(){
		// 用于输出的有效的提醒信息
		$valid_notification = [];

		$notification = Cookie::get($this->notification_cookie_name);
		settype($notification, 'array');

		foreach ($notification as $type => $notification_info) {
			if ($type == 'post'){
				foreach ($notification_info as $post_id => $update_info) {
					$post_content = $this->post_model->find('content', ['id=' => $post_id]);
					if (!empty($post_content)){
						$valid_notification[$type][$post_id] = $update_info;

						$post_content = htmlspecialchars($post_content['content']);
						$post_content = Text::shortThePost($post_content, 200);
						$valid_notification[$type][$post_id]['content'] = $post_content;
					}
				}
			}
			if ($type == 'collection'){
				foreach ($notification_info as $collection_id => $update_info) {
					$collection_info = $this->collection_model->find('name', ['id=' => $collection_id]);
					if (!empty($collection_info)){
						$valid_notification[$type][$collection_id] = $update_info;

						$valid_notification[$type][$collection_id]['name'] = htmlspecialchars($collection_info['name']);
					}
				}
			}
		}
		echo $this->view->make('notify.php')->with([
			'title' => '提醒 - ' . Config::get('app.title'),
			'notification' => $valid_notification,
		]);
	}

	public function post(){
		$notification = Cookie::get($this->notification_cookie_name, []);
		settype($notification, 'array');
		$return = [];

		// 关注的 post 有更新时提醒
		$post_update_flag = 0;
		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		foreach ($notify_post as $post_id => $timestamp) {
			$post_id = intval($post_id);
			$time = date('Y-m-d H:i:s', $timestamp);

			$has_update = $this->discuss_model->find('id', [
					'post_id=' => $post_id,
					'and discuss_time>' => $time,
				], 'order by discuss_time asc'
			);

			$update_count = $this->discuss_model->find('count(*) as count', [
					'post_id=' => $post_id,
					'and discuss_time>' => $time,
				]
			)['count'];
			if ($update_count > 0){
				$post_update_flag += 1;

				$notification['post'][$post_id] = [
					'from_timestamp' => $timestamp,
					'count' => $update_count,
				];
			}
		}

		// 关注的 collection 有新 post 时提醒
		$collection_update_flag = 0;
		$notify_collection = Cookie::get($this->notify_collection_cookie_name, []);
		settype($notify_collection, 'array');

		foreach ($notify_collection as $collection_id => $timestamp) {
			$collection_id = intval($collection_id);
			$time = date('Y-m-d H:i:s', $timestamp);

			$update_count = $this->post_model->find('count(*) as `count`', [
					'collection_id=' => $collection_id,
					'and published>' => $time,
				]
			)['count'];

			if ($update_count > 0){
				$collection_update_flag += 1;

				$notification['collection'][$collection_id] = [
					'from_timestamp' => $timestamp,
					'count' => $update_count,
				];
			}
		}

		if ($post_update_flag + $collection_update_flag > 0){
			Cookie::set($this->notification_cookie_name, $notification);
		}

		$return['notify']['has_update'] = $collection_update_flag + $post_update_flag;

		// 首页提醒; 首页只提醒新 post, 对于某一个 post 有更新时不提醒
		$index_update_flag = 0;
		$max_post = date('Y-m-d H:i:s', intval(Cookie::get($this->max_post_cookie_name)));
		$update_post_count = $this->post_model->find('count(*) as `count`', ['published>' => $max_post]);
		if (!empty($update_post_count['count'])){
			$index_update_flag = $update_post_count['count'];
			$return['post']['highlight_min'] = strtotime($max_post);
		}
		$return['post']['has_update'] = $index_update_flag;

		header("Content-Type: application/json");
		echo json_encode($return);
	}
}

$notifyHandler = new NotifyHandler();

$methods = ['get', 'post'];
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (!in_array($method, $methods))
	exit("不支持 {$method}");

$notifyHandler->$method();
