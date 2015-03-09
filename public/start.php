<?php
if (!defined('IN_SYSTEM'))
	exit;

if (!defined('ROOT'))
	define('ROOT', __DIR__ . '/..');

date_default_timezone_set('Asia/Shanghai');

ini_set('display_errors', 0);

include ROOT . '/core/Autoloader.php';

Autoloader::registerAutoloader();
Autoloader::addDir([
	ROOT . '/core',
	ROOT . '/app/model',
	ROOT . '/app/util',
	ROOT . '/public',
]);

Config::addConfig(ROOT . '/app/config/app.php', 'app');
Config::addConfig(ROOT . '/app/config/database.php', 'database');

Cookie::setTimeout(function(){
	return strtotime(date('Y-m-d 23:59:59')) - time();
});
// Cookie::setTimeout(5);
