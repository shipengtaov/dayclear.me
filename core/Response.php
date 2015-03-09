<?php

class Response{
	public function redirect($url, $halt=true){
		header('Location: ' . $url);
		if ($halt)
			exit($url);
	}
}
