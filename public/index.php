<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class IndexHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->max_post_cookie_name = Config::get('app.cookie.max_post');
	}

	public function get(){
		$page_param = "p";
		$p = isset($_GET[$page_param]) 
				&& is_numeric($_GET[$page_param]) 
				&& $_GET[$page_param] > 0
			? $_GET[$page_param]
			: 1;
		$limit = isset($_GET['limit']) 
					&& is_numeric($_GET['limit']) 
					&& $_GET['limit'] > 0 
					&& $_GET['limit'] <= 1000
				? $_GET['limit']
				: 50;

		$start = ($p-1) * $limit;
		$posts = $this->post_model->select('*', null, 
				'order by id desc limit ' . $start . ',' . $limit);

		if (!empty($posts) && $start == 0){
			Cookie::set($this->max_post_cookie_name, $posts[0]['id']);
		} else 
		if (empty($posts) && $start == 0){
			Cookie::set($this->max_post_cookie_name, 0);
		}
		foreach ($posts as &$post) {
			$post['content'] = Text::shortThePost($post['content'], 200);
			$post['discuss'] = $this->discuss_model->find(
					'count(*) as `count`', 
					['post_id=' => $post['id']]
			)['count'];
			$post['user'] = !empty($post['user']) ? $post['user'] : 'Annoymous';
			$post['published'] = date('H:i:s', strtotime($post['published']));
			$post = $this->filterPost($post);
		}

		// page
		$total_count = $this->post_model->find('count(*) as `count`')['count'];
		$total_page = max(1, ceil($total_count/$limit));
		$pre_page = ($p-1) > 0 ? ($p-1) : null;
		$next_page = ($p<$total_page) ? ($p+1) : null;
		$pagination = new Pagination($this->request->uri(), $p, $total_page,
									$page_param);
		$pagination_html = $pagination->get_html();

		echo $this->view->make('index.php')->with([
			'highlight_id' => intval($this->request->get_argument('hlid')),
			'highlight_from_id' => intval($this->request->get_argument('hlfid')),
			'posts' => $posts,
			'pagination_html' => $pagination_html,
		]);
	}

}

$post = new IndexHandler();

$method = strtolower($_SERVER['REQUEST_METHOD']);
if (method_exists($post, $method)){
	$post->$method();
	exit;
}
echo "不支持 {$method}";
