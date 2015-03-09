<?php

header('Location: /404.php');
exit;

if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class MissionHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
	}

	public function get(){
		echo $this->view->make('mission.php')->with([
			'title' => '愿景 - ' . Config::get('app.title'),
		]);
	}
}

$methods = ['get'];
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (!in_array($method, $methods)){
	exit("不支持 {$method}");
}

$sourceHandler = new MissionHandler();
$sourceHandler->$method();
