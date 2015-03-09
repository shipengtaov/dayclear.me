<?php

class Autoloader{
	public static $dirs;

	public static function addDir($dir){
		if (!isset(self::$dirs))
			self::$dirs = array();
		if (is_string($dir)){
			self::$dirs[] = $dir;
		} else {
			self::$dirs = array_merge(self::$dirs, $dir);
		}
	}

	public static function loader($origin_class){
		$class = trim(str_replace('\\', '/', $origin_class), '/');
		$found = false;
		foreach (self::$dirs as $dir) {
			$class_file = $dir . '/' . $class . '.php';
			if (file_exists($class_file)){
				$found = true;
				break;
			}
		}
		if (!$found){
			throw new Exception("class {$origin_class} does not exist");
		}
		include $class_file;
	}

	public static function registerAutoloader(){
		spl_autoload_register(array('Autoloader', 'loader'));
	}
}


