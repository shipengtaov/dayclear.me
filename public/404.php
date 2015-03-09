<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class NotFoundHandler extends BaseHandler{
	public function get(){
		echo $this->view->make("404.php");
		return;
	}
}

$methods = ['get'];
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (!in_array($method, $methods)){
	echo "{$method} 不支持";
	exit;
}
$notFoundHandler = new NotFoundHandler();
$notFoundHandler->$method();

