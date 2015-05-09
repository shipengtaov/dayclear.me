<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class IndexHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->collection_model = new CollectionModel();
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
		$posts = $this->getPostAndFormat(null, [$start, $limit]);
		// $posts = $this->post_model->select('*', null, 
		// 		'order by id desc limit ' . $start . ',' . $limit);

		$update_max_post_timestamp = 0;
		if (!empty($posts) && $start == 0){
			$update_max_post_timestamp = strtotime($posts[0]['update_time']);
		} else 
		if (empty($posts) && $start == 0){
			$update_max_post_timestamp = time();
		}
		if ($update_max_post_timestamp < time())
			$update_max_post_timestamp = time();

		Cookie::set($this->max_post_cookie_name, $update_max_post_timestamp);

		// page
		$pagination_html = $this->getPaginationHtml([
			'count' => $this->post_model->find('count(*) as count')['count'],
			'current_page' => $p,
			'limit' => $limit,
			'page_param' => $page_param,
		]);
		echo $this->view->make('index.php')->with([
			'highlight_id' => intval($this->request->get_argument('hlid')),
			'highlight_from_time' => $this->request->get_argument('hlftime') ? date('H:i:s', $this->request->get_argument('hlftime')) : null,
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
