<?php
// if (!defined('IN_SYSTEM'))
// 	define('IN_SYSTEM', true);
// include 'start.php';
if (!defined('IN_SYSTEM'))
	header('Location: /404.php');

class AboutHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
	}

	public function get(){
		echo $this->view->make('about.php')->with([
			'title' => '关于 - ' . Config::get('app.title'),
		]);
	}

}

// $post = new AboutHandler();

// $method = strtolower($_SERVER['REQUEST_METHOD']);
// if (method_exists($post, $method)){
// 	$post->$method();
// 	exit;
// }
// echo "不支持 {$method}";
