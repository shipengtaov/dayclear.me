<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class PostHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->notify_model = new NotifyModel();

		$this->username_length_limit = Config::get('app.length_limit.username');
		$this->post_length_limit = Config::get('app.length_limit.post');
	}

	public function get(){
		$error_message = Util::get_error_message();
		Util::set_error_message(null);

		$cookie_username = Cookie::get(Config::get('app.cookie.username'));
		$username = !empty($cookie_username) && $cookie_username != Config::get('app.default_username')
						? $cookie_username : '';

		$id = $this->request->get_argument('id');

		if (!$id){
			echo $this->view->make('post.php')->with([
				'title' => '发表 - ' . Config::get('app.title'),
				'xsrf_form_html' => Util::xsrf_form_html(),
				'error_message' => $error_message,
				'username' => $username,
				// 'inject_head' => '<style>body{background-color:green;}</style>',
			]);
			return;
		}

		# with id
		$post = $this->post_model->find('*', ['id=' => $id]);
		if (empty($post)){
			return $this->notFound();
		}
		// filter specialchars
		$post = $this->filterPost($post);

		// 提前生成 title
		$title = Text::shortThePost($post['content'], 30) . ' - ' . Config::get('app.title');

		// [url] 标签
		// generate url link
		$post['content'] = Text::makeURLLinkFromStr($post['content']);

		// 提取 [img] 标签
		list($images, $origin_content, $after_replace_content) = Text::detectIMGTag($post['content']);
		// if (!empty($images))
		// 	$post['content'] = $after_replace_content;

		// 更新 notify 时间
		$this->notify_model->updateNotifyPost($post['id']);
		$this->notify_model->markReadOne($post['id']);

		/* order by discuss_time asc, asc 重要 */
		$discuss = $this->discuss_model->select('*', 
				['post_id=' => $id],
				'order by discuss_time asc'
		);
		foreach ($discuss as &$row) {
			$row['discuss_time'] = date('H:i:s', strtotime($row['discuss_time']));
			$row = $this->filterDiscuss($row);
			$row['content'] = Text::makeURLLinkFromStr($row['content']);
		}
		list($discuss, $reply) = $this->formatDiscuss($discuss);
		echo $this->view->make('post_detail.php')->with([
			'error_message' => $error_message,
			'xsrf_form_html' => Util::xsrf_form_html(),
			'highlight_id' => $this->request->get_argument('hlid'),
			'highlight_from_id' => $this->request->get_argument('hlfid'),
			// 'highlight_reply_id' => $this->request->get_argument('hlrid'),
			'title' => $title,
			'username' => $username,
			'has_follow' => $this->notify_model->hasFollowPost($post['id']),
			'post' => $post,
			'images' => $images,
			'discuss' => $discuss,
			'reply' => $reply,
		]);
	}

	public function post(){
		Util::set_error_message(null);
		if ($this->hasReachLimit($this->request->ip(), Config::get('app.daily_limit'))){
			Util::set_error_message('已到达每天发表的最大数量');
			$this->response->redirect('/post.php');
		}
		$user = $this->request->post_argument('username', Config::get('app.default_username'));
		$content = trim($this->request->post_argument('content'));
		if (empty($content)){
			Util::set_error_message("未填写内容");
			return $this->response->redirect('/post.php');
		}
		if (mb_strlen($user, 'utf-8') > $this->username_length_limit){
			Util::set_error_message("用户名最大允许长度为 {$this->username_length_limit}");
			return $this->response->redirect('/post.php');
		}
		if (mb_strlen($content, 'utf-8') > $this->post_length_limit){
			Util::set_error_message("最大允许长度为 {$this->post_length_limit}");
			return $this->response->redirect('/post.php');
		}
		if (!empty($user)){
			if ($user == Config::get('app.default_username'))
				Cookie::setRaw(Config::get('app.cookie.username'), '', time()-1);
			else
				Cookie::set(Config::get('app.cookie.username'), $user);
		}

		$res = $this->post_model->insert(
			array(
				"user" => $user,
				"content" => $this->request->post_argument('content'),
				"ip" => $this->request->ip(),
				"published" => date('Y-m-d H:i:s'),
			)
		);
		if (!$res){
			Util::set_error_message("发表失败了");
			return $this->response->redirect('/post.php');
		}
		// 添加需要提醒的 post
		$this->notify_model->addPublishPost($res);
		return $this->response->redirect('/?hlid=' . $res);
	}

	/**
	 * @return boolean true: reach limit; false: not reach limit
	 */
	public function hasReachLimit($ip, $max){
		$count = $this->post_model->find('count(*) as `count`', ['ip=' => $ip])['count'];
		return (bool)($count >= $max);
	}

	/**
	 * 依赖于 $discuss 顺序, 否则用户回复楼层时会不正确
	 */
	public function formatDiscuss($discuss){
		$parent_discuss = [];
		$reply_discuss = [];

		foreach ($discuss as $k => $v) {
			if (empty($v['reply_id']))
				$parent_discuss[] = $v;
			else
				$reply_discuss[] = $v;
		}
		foreach ($reply_discuss as $k => $reply) {
			unset($reply_discuss[$k]);
			$reply_discuss[$reply['reply_id']][] = $reply;
		}
		return [$parent_discuss, $reply_discuss];
	}

}

$methods = ['get', 'post'];
$method = strtolower($_SERVER['REQUEST_METHOD']);

if (!in_array($method, $methods)){
	echo "不支持 {$method}";
	exit;
}

$postHandler = new PostHandler();
$postHandler->$method();
