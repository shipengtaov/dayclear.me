<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class NotifyHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->notify_model = new NotifyModel();

		$this->notify_post_cookie_name = Config::get('app.cookie.notify_post');
		$this->notification_cookie_name = Config::get('app.cookie.notification');
		$this->max_post_cookie_name = Config::get('app.cookie.max_post');
	}

	public function get(){
		// $error_message = Util::get_error_message();
		// Util::set_error_message(null);
		$notification = Cookie::get($this->notification_cookie_name);
		settype($notification, 'array');
		// $this->notify_model->markReadAll();
		foreach ($notification as $post_id => $update_info) {
			$post_content = $this->post_model->find('content', ['id=' => $post_id])['content'];
			$post_content = htmlspecialchars($post_content);
			$post_content = Text::shortThePost($post_content, 200);
			$notification[$post_id]['post_content'] = $post_content;
		}
		echo $this->view->make('notify.php')->with([
			'title' => '提醒 - ' . Config::get('app.title'),
			'notification' => $notification,
		]);
	}

	public function post(){
		Util::set_error_message(null);
		$notification = Cookie::get($this->notification_cookie_name, []);
		settype($notification, 'array');
		$notify_post = Cookie::get($this->notify_post_cookie_name, []);
		settype($notify_post, 'array');

		$notify_update_flag = 0;
		$post_update_flag = 0;
		foreach ($notify_post as $post_id => $timestamp) {
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
			);
			if (!empty($has_update)){
				$notify_update_flag += 1;

				// if (!in_array($post_id, $notification)){
				// 	$notification[] = $post_id;
				// }
				$notification[$post_id] = [
					'from_id' => $has_update['id'],
					'count' => $update_count['count'],
				];
			}
		}
		if ($notify_update_flag > 0){
			Cookie::set($this->notification_cookie_name, $notification);
		}
		$return['notify']['has_update'] = $notify_update_flag;

		// check new post
		$max_post = intval(Cookie::get($this->max_post_cookie_name));
		$update_post_count = $this->post_model->find('count(*) as `count`', ['id>' => $max_post]);
		if (!empty($update_post_count['count'])){
			$post_update_flag = $update_post_count['count'];
			$return['post']['highlight_min'] = $max_post;
		}
		$return['post']['has_update'] = $post_update_flag;
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
