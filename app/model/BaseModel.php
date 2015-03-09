<?php

class BaseModel extends Model{
	public static $con;

	public function __construct(){
		if (!isset(self::$con)){
			self::$con = parent::getInstance([
				'host' => Config::get('database.default.host'),
				'dbname' => Config::get('database.default.dbname'),
				'user' => Config::get('database.default.user'),
				'password' => Config::get('database.default.password'),
			]);
		}
		parent::__construct(self::$con);
	}
}
