<?php

class FollowCollectionHandler extends BaseHandler{
	public function __construct(){
		parent::__construct();
		$this->post_model = new PostModel();
		$this->discuss_model = new DiscussModel();
		$this->collection_model = new CollectionModel();
		$this->notify_model = new NotifyModel();
	}

	public function get(){
		$collections = $this->notify_model->getAllFollowCollection();
		$collection_info = [];
		foreach ($collections as $collection_id => $timestamp) {
			$collection_id = intval($collection_id);
			$tmp_collection = $this->collection_model->find('*', ['id=' => $collection_id]);
			if (!empty($tmp_collection))
				$collection_info[] = $tmp_collection;
		}

		echo $this->view->make('follow_collection.php')->with([
			'title' => '关注的合集 - ' . Config::get('app.title'),
			'collections' => $collection_info,
		]);
	}

	public function post(){
		header('Content-Type: application/json');

		$valid_actions = ['follow', 'unfollow'];

		$action = $this->request->post_argument('action');
		$collection_id = intval($this->request->post_argument('collection_id'));

		if (!in_array($action, $valid_actions)){
			echo json_encode(['code' => -1, 'msg' => 'invalid action']);
			return;
		}

		if (empty($this->collection_model->find('id', ['id=' => $collection_id]))){
			echo json_encode(['code' => -1, 'msg' => '合集不存在']);
			return;
		}

		if ($action == 'follow'){
			$this->notify_model->addNotifyCollection($collection_id);
		} else
		if ($action == 'unfollow'){
			$this->notify_model->deleteNotifyCollection($collection_id);
		}
		echo json_encode(['code' => 0]);
		return;
	}
}
