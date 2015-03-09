<?php
header('Location: /404.php');
// define('IN_SYSTEM', true);
include 'start.php';

Route::get("#^/post-?(\d+)?-?(\w+)?$#i", function($id=null, $name=null){
	$post = new Post();
	$post->get($id, $name);
});

// Route::setNotFound(function(){
// 	echo "page not found";
// });
$request = new Request();
Route::dispatch($request->uri(), $request->method());

echo '<pre>';

$str = '/po(st/3432';
$pattern = '#^/po\(s(?:t)/?(\d+)?$#i';
preg_match($pattern, $str, $matches);
print_r($matches);
echo "<br>";
print_r(howManyParameter($pattern));
echo '</pre>';



/**
 * Route:
 * /post/(\d+)
 */

// $cookies = Cookie::getAll();
// foreach ($cookies['notify_post'] as &$value) {
// 	$value = date('Y-m-d H:i:s', $value);
// }

// echo '<pre>';

// print_r($cookies);

// echo '</pre>';
