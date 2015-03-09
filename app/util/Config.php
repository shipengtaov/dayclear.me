<?php

class Config{
	public static $config;
	
	/**
	 * Config::get("app.daily_limit")
	 *                  -> self::$config['app']['daily_limit']
	 */
	public static function get($key){
		$key_arr = explode('.', $key);
		if (!isset(self::$config[$key_arr[0]]))
			throw new Exception("{$key} config not exist");
		$get_config = self::$config[array_shift($key_arr)];
		foreach ($key_arr as $child_key) {
			if (!isset($get_config[$child_key]))
				throw new Exception("{$key} config not exist");
			$get_config = $get_config[$child_key];
		}
		return $get_config;
	}

	public static function addConfig($file, $key){
		if (!file_exists($file))
			throw new Exception(__CLASS . " : {$file} does not exist");
		self::$config[$key] = include $file;
	}
}
