<?php

class Util{
	public static function get_xsrf(){
		session_start();
		if (isset($_SESSION['xsrf']) && $_SESSION['xsrf']){
			$xsrf =  $_SESSION['xsrf'];
			session_write_close();
			return $xsrf;
		}
		$xsrf = uniqid();
		$_SESSION['xsrf'] = $xsrf;
		session_write_close();
		return $xsrf;
	}

	public static function clear_xsrf(){
		session_start();
		unset($_SESSION['xsrf']);
		session_write_close();
	}

	public static function xsrf_form_html(){
		$xsrf = self::get_xsrf();
		return '<input type="hidden" name="xsrf" value="' . $xsrf . '">';
	}

	/**
	 * set error_message to view
	 */
	public static function set_error_message($value, $key='error_message'){
		session_start();
		$_SESSION[$key] = $value;
		session_write_close();
	}

	/**
	 * get error_message from session
	 */
	public static function get_error_message($key='error_message'){
		session_start();
		$error_message = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
		session_write_close();
		return $error_message;
	}
}
