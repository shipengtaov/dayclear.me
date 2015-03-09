<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class CodeWorldHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
	}

	public function get(){
		echo $this->view->make('codeworld.php')->with([
			'title' => 'code - ' . Config::get('app.title'),
		]);
	}

	public function post(){
		exit;
	}
}

$post = new CodeWorldHandler();

$method = strtolower($_SERVER['REQUEST_METHOD']);
if (method_exists($post, $method)){
	$post->$method();
	exit;
}
echo "不支持 {$method}";
