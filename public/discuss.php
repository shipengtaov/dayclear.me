<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class DiscussHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->notify_model = new NotifyModel();

		$this->username_length_limit = Config::get('app.length_limit.username');
		$this->discuss_length_limit = Config::get('app.length_limit.discuss');
		$this->reply_length_limit = Config::get('app.length_limit.reply');
	}

	public function get(){
		$this->response->redirect('/');
	}

	public function post(){
		Util::set_error_message(null);
		$post_id = $this->request->post_argument('post_id');
		$reply_id = intval($this->request->post_argument('reply_id'));
		$user = $this->request->post_argument('username', Config::get('app.default_username'));
		$content = trim($this->request->post_argument('content'));
		if ( empty($post_id)
			|| empty($content)){
			Util::set_error_message("请填写完整数据");
			return $this->response->redirect('/post.php?id=' . $post_id);
		}
		$post = $this->post_model->find('id', ['id=' => $post_id]);
		if (empty($post)){
			exit("{$post_id} 不存在");
		}

		if (!empty($reply_id) && $reply_id > 0){
			$discuss = $this->discuss_model->find('id', ['id=' => $reply_id]);
			if (empty($discuss)){
				exit("{$reply_id} 不存在");
			}
		}

		// length limit
		if (mb_strlen($user, 'utf-8') > $this->username_length_limit){
			Util::set_error_message("用户名最大允许长度为 {$this->username_length_limit}");
			return $this->response->redirect('/post.php?id=' . $post_id);
		}
		if (empty($reply_id)){
			// discuss limit
			if (mb_strlen($content, 'utf-8') > $this->discuss_length_limit){
				Util::set_error_message("最大允许长度为 {$this->discuss_length_limit}");
				return $this->response->redirect('/post.php?id=' . $post_id);
			}
		} else {
			// reply limit
			if (mb_strlen($content, 'utf-8') > $this->reply_length_limit){
				Util::set_error_message("最大允许长度为 {$this->reply_length_limit}");
				return $this->response->redirect('/post.php?id=' . $post_id);
			}
		}

		if (!empty($user)){
			if ($user == Config::get('app.default_username'))
				Cookie::setRaw(Config::get('app.cookie.username'), '', time()-1);
			else
				Cookie::set(Config::get('app.cookie.username'), $user);
		}

		$res = $this->discuss_model->insert(
			array(
				"post_id" => $post['id'],
				"reply_id" => $reply_id,
				"user" => $user,
				"content" => $content,
				"ip" => $this->request->ip(),
				"discuss_time" => date('Y-m-d H:i:s'),
			)
		);
		if (empty($res)){
			Util::set_error_message("评论发表失败");
			return $this->response->redirect("/post.php?id=" . $post_id);
		}

		// 更新 post update_time
		$this->post_model->save([
			"update_time" => date('Y-m-d H:i:s'),
		]);
		
		// add or update notify timestamp
		$this->notify_model->addNotifyPost($post['id']);
		return $this->response->redirect('/post.php?id=' . $post_id . '&hlid=' . $res . '#' . $res);
	}

}

$discussHandler = new DiscussHandler();

$methods = ['get', 'post'];
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (!in_array($method, $methods)){
	echo "{$method} 不支持";
	exit;
}
$discussHandler->$method();
