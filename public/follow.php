<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class FollowHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->notify_model = new NotifyModel();
	}

	public function get(){
		$error_message = Util::get_error_message();
		Util::set_error_message(null);

		$notify_post = $this->notify_model->getAllFollowPost();

		$update_notify_post_cookie_flag = false;
		$follow_list = [];
		foreach ($notify_post as $post_id => $timestamp) {
			$post_id = intval($post_id);
			$post = $this->post_model->find('*', ['id=' => $post_id]);

			if (empty($post)){
				// 清除无效 post
				$update_notify_post_cookie_flag = true;
				unset($notify_post[$post_id]);
				continue;
			}

			$post = $this->filterPost($post);
			$post['content'] = Text::shortThePost($post['content'], 200);
			$follow_list[$post_id] = $post;
		}

		if ($update_notify_post_cookie_flag)
			$this->notify_model->update('notify_post', $notify_post);

		echo $this->view->make('follow.php')->with([
			'title' => '关注列表 - ' . Config::get('app.title'),
			'follow_list' => $follow_list,
		]);
	}

	public function post(){
		$actions = ['follow', 'unfollow'];

		$post_id = intval($this->request->post_argument('post_id'));
		$action = strval($this->request->post_argument('action'));

		header("Content-Type:application/json");

		if (!in_array($action, $actions)){
			echo json_encode(['code' => -1, 'msg' => 'invalid action']);
			return;
		}

		$post = $this->post_model->find('id', ['id=' => $post_id]);
		if (empty($post)){
			echo json_encode(['code' => -1, 'msg' => 'post doesn\'t exist']);
			return;
		}

		if ($action == 'follow'){
			$this->notify_model->addNotifyPost($post_id);
		} else
		if ($action == 'unfollow'){
			$this->notify_model->deleteNotifyPost($post_id);
		}
		echo json_encode(['code' => 0]);
		return;
	}

}

$methods = ['get', 'post'];
$method = strtolower($_SERVER['REQUEST_METHOD']);

if (!in_array($method, $methods)){
	echo "不支持 {$method}";
	exit;
}

$followHandler = new FollowHandler();
$followHandler->$method();
