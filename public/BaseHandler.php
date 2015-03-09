<?php
if (!defined('IN_SYSTEM'))
	exit;

class BaseHandler{
	public $request;
	public $response;

	public $base_model;
	public $view;

	public function __construct(){
		$this->request = new Request();
		$this->response = new Response();
		$this->base_model = new BaseModel();
		$this->view = new View(__DIR__ . '/../app/view/');

		$this->globalViewVars();
	}

	public function globalViewVars(){
		if (!empty(Cookie::get('notification'))){
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
}
