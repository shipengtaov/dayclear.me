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
	ROOT . '/app/handler',
	ROOT . '/app/util',
	ROOT . '/public',
]);

Environment::setEnvironmentOption(['file' => ROOT . '/app/config/app.php']);

Config::setConfigDir(ROOT . '/app/config');
Config::addConfig('app.php', 'app', Environment::getEnvironment());
Config::addConfig('database.php', 'database', Environment::getEnvironment());

Cookie::setTimeout(function(){
	return strtotime(date('Y-m-d 23:59:59')) - time();
});
Cookie::setPath('/');
// Cookie::setTimeout(5);
