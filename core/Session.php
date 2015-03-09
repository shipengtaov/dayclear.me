<?php

class Session{
	public static function getRaw($name, $default=null){
		session_start();
		$value = isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
		session_write_close();
		return $value;
	}

	public static function get($name, $default=null, $clear=false){
		session_start();
		if (isset($_SESSION[$name])){
			if (isset($_SESSION[$name]['expire']) && $_SESSION[$name]['expire'] < time()){
				unset($_SESSION[$name]);
				$value = $default;
			} else {
				$value = $_SESSION[$name]['value'];
				if ($clear)
					unset($_SESSION[$name]);
			}
		} else {
			$value = $default;
		}
		session_write_close();
		return $value;
	}

	public static function setRaw($name, $value){
		session_start();
		$_SESSION[$name] = $value;
		session_write_close();
	}

	public static function set($name, $value, $expire=null){
		session_start();
		$_SESSION[$name]['value'] = $value;
		if (!is_null($expire))
			$_SESSION[$name]['expire'] = $expire;
		session_write_close();
	}

	public static function clear($name){
		session_start();
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
		session_write_close();
	}
}
