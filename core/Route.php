<?php

class Route{
	public static $get;
	public static $post;

	public static $uri;
	public static $path;

	public static $method;

	public static $not_found;

	public static function get($pattern, $callback){
		if (is_string($pattern)){
			self::$get[$pattern] = $callback;
		} else 
		if (is_array($pattern)){
			foreach ($pattern as $pattern_item) {
				self::$get[$pattern_item] = $callback;
			}
		}
	}

	public static function post($pattern, $callback){
		if (is_string($pattern)){
			self::$post[$pattern] = $callback;
		} else 
		if (is_array($pattern)){
			foreach ($pattern as $pattern_item) {
				self::$post[$pattern_item] = $callback;
			}
		}
	}

	public static function dispatch($uri, $method){
		self::$uri = $uri;
		self::$path = rtrim(parse_url(self::$uri)['path'], '/');
		if (substr(self::$path, 0, 1) != '/')
			self::$path = '/' . self::$path;
		self::$method = strtolower($method);

		if (self::$method == 'get'){
			return self::matchGet(self::$path);
		} else
		if (self::$method == 'post'){
			return self::matchPost(self::$path);
		}
	}

	public static function matchGet($path){
		if (!isset(self::$get))
			self::$get = [];
		return self::matchPattern($path, self::$get);
	}

	public static function matchPost($path){
		if (!isset(self::$post))
			self::$post = [];
		return self::matchPattern($path, self::$post);
	}

	public static function matchPattern($path, $pattern_callback){
		foreach ($pattern_callback as $pattern => $callback) {
			if (!preg_match($pattern, $path, $matches)){
				continue;
			}
			$parameters = self::buildParameter($pattern, $matches);

			return call_user_func_array($callback, $parameters);
		}
		if (isset(self::$not_found))
			return call_user_func(self::$not_found);
	}

	public static function buildParameter($pattern, $matches){
		array_shift($matches);

		$param_count = self::howManyParameter($pattern);
		for ($i=0; $i < $param_count; $i++) { 
			if (!isset($matches[$i]))
				$matches[$i] = null;
		}

		return $matches;
	}

	public static function howManyParameter($pattern){
		$pattern = str_replace('\(', '', $pattern);
		$pattern = str_replace('(?:', '', $pattern);
		$pattern = str_replace('(?#', '', $pattern);

		$before_replace_len = strlen($pattern);

		$after_pattern = str_replace('(', '', $pattern);

		$after_replace_len = strlen($after_pattern);

		return ($before_replace_len - $after_replace_len);
	}

	public static function setNotFound($callback){
		self::$not_found = $callback;
	}
}
