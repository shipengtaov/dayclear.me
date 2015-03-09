<?php

class String{
	public static function startswith($string, $start){
		if (strlen($start) > strlen($string))
			return false;
		if (substr($string, 0, strlen($start)) == $start)
			return true;
		else
			return false;
	}

	public static function endswith($string, $end){
		if (strlen($end) > strlen($string))
			return false;
		if (substr($string, -strlen($end)) == $end)
			return true;
		else
			return false;
	}
}
