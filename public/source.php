<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include 'start.php';

class SourceHandler extends BaseHandler{

	public function __construct(){
		parent::__construct();
		$this->source = "https://github.com/shispt/dayclear.me";
	}

	public function get(){
		echo $this->view->make('source.php')->with([
			'title' => 'source code - ' . Config::get('app.title'),
			'source' => $this->source,
		]);
	}

}

$methods = ['get'];
$method = strtolower($_SERVER['REQUEST_METHOD']);

if (!in_array($method, $methods)){
	echo "不支持 {$method}";
	exit;
}

$sourceHandler = new SourceHandler();
$sourceHandler->$method();
