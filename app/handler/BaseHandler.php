<?php

class BaseHandler{
	public $request;
	public $response;

	public $base_model;
	public $view;

	public function __construct(){
		$this->request = new Request();
		$this->response = new Response();
		$this->base_model = new BaseModel();
		$this->view = new View(__DIR__ . '/../view');

		$this->globalViewVars();
	}

	public function globalViewVars(){
		$notification = Cookie::get('notification');
		settype($notification, 'array');

		if (!empty($notification['post']) or !empty($notification['collection'])){
			$this->view->shareWith(
				['has_notify' => true],
				'all'
			);
		}
		
		# check new post
		$max_post = intval(Cookie::get('max_post'));
		$min_update_post = $this->base_model->query("select * from post where id>'{$max_post}' limit 1")->fetch(PDO::FETCH_ASSOC);
		if (!empty($min_update_post)){
			$this->view->shareWith(
				[
					'has_new_post' => true
				],
				[
					'-' => ['index.php']
				]
			);
		}
	}

	public function filterPost($post){
		$filtered = $post;
		$filtered['user'] = htmlspecialchars($filtered['user']);
		$filtered['content'] = htmlspecialchars($filtered['content']);
		return $filtered;
	}

	public function filterDiscuss($discuss){
		$filtered = $discuss;
		$filtered['user'] = htmlspecialchars($filtered['user']);
		$filtered['content'] = htmlspecialchars($filtered['content']);
		return $filtered;
	}

	public function notFound(){
		echo $this->view->make("404.php");
		return;
	}

	public function getPostAndFormat($where, $limit=null){
		if (is_null($limit))
			$limit = [0, 50];
		$posts = $this->post_model->select('*', $where, 
				'order by update_time desc, published desc limit ' . $limit[0] . ',' . $limit[1]);
		foreach ($posts as &$post) {
			$post['content'] = Text::shortThePost($post['content'], 200);
			$post['discuss'] = $this->discuss_model->find(
					'count(*) as `count`', 
					['post_id=' => $post['id']]
			)['count'];
			$post['user'] = !empty($post['user']) ? $post['user'] : 'Annoymous';
			$post['published'] = date('H:i:s', strtotime($post['published']));
			$post = $this->filterPost($post);
			$post['collection'] = htmlspecialchars(
				$this->collection_model->find('name',['id=' => $post['collection_id']])['name']
			);
		}
		return $posts;
	}

	/**
	 * @param  array $option ['count' => int, 'current_page' => int, 'limit' => int, 'page_param' => xxx]
	 * @return string : pagination html
	 */
	public function getPaginationHtml($option){
		if (!isset($option['where']))
			$option['where'] = null;

		$total_count = $option['count'];
		$total_page = max(1, ceil($total_count/$option['limit']));

		$pagination = new Pagination($this->request->uri(), $option['current_page'], $total_page,
									$option['page_param']);
		return $pagination->get_html();
	}
}
