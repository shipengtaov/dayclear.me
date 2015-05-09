<?php

class Config{
	public static $config_dir;
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

	public static function setConfigDir($dir){
		self::$config_dir = $dir;
	}

	public static function addConfig($file, $key, $local_dir=null){
		if (!file_exists(self::$config_dir . '/' . $file))
			throw new Exception(__CLASS__ . ' : ' . self::$config_dir . '/' . $file . ' does not exist');
		self::$config[$key] = include self::$config_dir.'/'.$file;
		if (!is_null($local_dir)){
			$local_config_file = self::$config_dir . '/' . $local_dir . '/' . $file;
			if (file_exists($local_config_file))
				self::$config[$key] = array_merge(self::$config[$key], include $local_config_file);
		}
	}
}
