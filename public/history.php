<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class HistoryHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->history_model = new HistoryModel();
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
		$history = $this->history_model->select('*', null, 
				'order by date desc limit ' . $start . ',' . $limit);

		// page
		$total_count = $this->history_model->find('count(*) as `count`')['count'];
		$total_page = max(1, ceil($total_count/$limit));
		$pre_page = ($p-1) > 0 ? ($p-1) : null;
		$next_page = ($p<$total_page) ? ($p+1) : null;
		$pagination = new Pagination($this->request->uri(), $p, $total_page,
									$page_param);
		$pagination_html = $pagination->get_html();

		echo $this->view->make('history.php')->with([
			'title' => '历史 - ' . Config::get('app.title'),
			'history' => $history,
			'pagination_html' => $pagination_html,
		]);
	}

}

$methods = ['get'];

$method = strtolower($_SERVER['REQUEST_METHOD']);
if (!in_array($method, $methods)){
	echo "不支持 {$method}";
	exit;
}

$history_handler = new HistoryHandler();
$history_handler->$method();
