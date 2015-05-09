<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

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

$methods = ['get'];
$method = strtolower($_SERVER['REQUEST_METHOD']);

if (!in_array($method, $methods)){
	echo "不支持 {$method}";
	exit;
}

$aboutHandler = new AboutHandler();
$aboutHandler->$method();
