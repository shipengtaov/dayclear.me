<?php

class CollectionHandler extends BaseHandler{
	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->collection_model = new CollectionModel();
		$this->notify_model = new NotifyModel();
	}

	public function get($collection_name){
		if (!$collection_name){
			return $this->getAll();
		}
		#
		$collection_name = urldecode($collection_name);

		# 检查是否存在
		$collection_info = $this->collection_model->find('*', ['name=' => $collection_name]);
		if (empty($collection_info)){
			# todo: 打印相关的合集
			return $this->response->redirect('/404.php');
		}

		// 更新提醒时间戳
		$this->notify_model->updateNotifyCollection($collection_info['id']);
		// 标记为已读
		$this->notify_model->markReadOne($collection_info['id'], 'collection');

		// 分页参数
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

		// 获取当前合集下的 post
		$posts = $this->getPostAndFormat(['collection_id=' => $collection_info['id']], [$start, $limit]);

		// page
		$pagination_html = $this->getPaginationHtml([
			'count' => $this->post_model->find('count(*) as count', ['collection_id=' => $collection_info['id']])['count'],
			'current_page' => $p,
			'limit' => $limit,
			'page_param' => $page_param,
		]);

		echo $this->view->make('collection_one.php')->with([
			'title' => $collection_info['name'] . ' - ' . Config::get('app.title'),
			'highlight_from_time' => $this->request->get_argument('hlftime') ? date('H:i:s', $this->request->get_argument('hlftime')) : null,
			'collection_info' => $collection_info,
			'has_follow' => $this->notify_model->hasFollowCollection($collection_info['id']),
			'posts' => $posts,
			'pagination_html' => $pagination_html,
		]);
	}

	public function getAll(){
		$order_bys = ['create_time', 'count'];
		$sorts = ['asc', 'desc'];

		$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], $order_bys) 
						? $_GET['order_by']
						: 'create_time';
		$sort = isset($_GET['sort']) && in_array($_GET['sort'], $sorts)
					? $_GET['sort']
					: ($order_by == 'create_time' ? 'asc' : 'desc');

		$collections = $this->collection_model->select('*', null, "order by `{$order_by}` {$sort}");
		foreach ($collections as &$collection) {
			$collection['name'] = htmlspecialchars($collection['name']);
		}

		echo $this->view->make('collection_all.php')->with([
			'title' => '所有合集 - ' . Config::get('app.title'),
			'current_order_by' => $order_by,
			'collections' => $collections,
		]);
	}
}
