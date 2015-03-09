<?php

class Cookie{
	public static $timeout;
	public static $path;
	public static $domain;
	public static $secure=false;
	public static $httponly=false;

	public static $prefix='';

	public static function getRaw($name, $default=null){
		if (isset($_COOKIE[$name]))
			return $_COOKIE[$name];
		return $default;
	}

	public static function get($name, $default=null){
		$name = self::$prefix.$name;

		if (isset($_COOKIE[$name])){
			return self::unBuildValue($_COOKIE[$name]);
		}
		return $default;
	}

	public static function getAll(){
		$cookie = [];
		foreach ($_COOKIE as $name => $value) {
			$cookie[$name] = self::unBuildValue($value);
		}
		return $cookie;
	}

	public static function setRaw($name, $value, $expire){
		setcookie($name, $value, $expire);
		$_COOKIE[$name] = $value;
	}

	/**
	 * @param  $timeout timeout 不保存入静态变量, 只作临时使用
	 */
	public static function set($name, $value, $timeout=null, $path=null, $domain=null){
		if (is_null($timeout))
			$timeout = self::getTimeout();
		if (!is_null($path))
			self::setPath($path);
		if (!is_null($domain))
			self::setDomain($domain);

		$name = self::$prefix.$name;
		$value = self::buildValue($value);

		if (isset(self::$path) && isset(self::$domain))
			setcookie($name, $value, time()+$timeout, self::$path, self::$domain);
		else if (isset(self::$path) && !isset(self::$domain))
			setcookie($name, $value, time()+$timeout, self::$path);
		else if (!isset(self::$path) && !isset(self::$domain))
			setcookie($name, $value, time()+$timeout);
		$_COOKIE[$name] = $value;
	}

	public static function buildValue($value){
		return json_encode($value);
	}

	public static function unBuildValue($builded){
		return json_decode($builded, true);
	}

	public static function clear($name){
		$name = self::$prefix.$name;

		if (isset(self::$path) && isset(self::$domain))
			setcookie($name, '', time()-1, self::$path, self::$domain);
		else if (isset(self::$path) && !isset(self::$domain))
			setcookie($name, '', time()-1, self::$path);
		else if (!isset(self::$path) && !isset(self::$domain))
			setcookie($name, '', time()-1);
		unset($_COOKIE[$name]);
	}

	public static function clearAll(){
		foreach ($_COOKIE as $k => $v) {
			self::clear($k);
		}
	}

	public static function setTimeout($timeout){
		self::$timeout = $timeout;
	}

	public static function getTimeout(){
		if (is_callable(self::$timeout))
			return call_user_func(self::$timeout);
		return self::$timeout;
	}

	public static function setPrefix($prefix){
		self::$prefix = $prefix;
	}

	public static function setPath($path){
		self::$path = $paht;
	}

	public static function setDomain($domain){
		self::$domain = $domain;
	}

	public static function setSecure($secure){
		self::$secure = (bool)$secure;
	}

	public static function setHttponly($httponly){
		self::$httponly = (bool)$httponly;
	}
}
