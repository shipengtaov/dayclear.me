<?php

class Request{
	public function get_argument($name, $default=null){
		if (isset($_GET[$name]) && $_GET[$name] !== '')
			return $_GET[$name];
		return $default;
	}

	public function post_argument($name, $default=null){
		if (isset($_POST[$name]) && $_POST[$name] !== '')
			return $_POST[$name];
		return $default;
	}

	public function method(){
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	public function uri(){
		return $_SERVER['REQUEST_URI'];
	}

	public function ip(){
		return $_SERVER['REMOTE_ADDR'];
	}
}
