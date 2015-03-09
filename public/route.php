<?php

define('IN_SYSTEM', true);
include 'start.php';

Route::get("#^/admin/login$#i", function(){
	if (Session::get(Config::get('app.session.admin'))){
		echo 'is admin';
		return;
	}
	$html = '<html><head>';
	$html .= '<http-equiv meta="Content-Type" content="text/html;charset=utf-8">';
	$html .= '</head><body>';
	if ($wrong_msg = Session::get('admin_wrong', null, true)){
		$html .= '<div>' . $wrong_msg . '</div>';
	}
	$html .= '<form action="/admin/login" method="post">';
	$html .= 'username: <input type="text" name="username" required="required"><br>';
	$html .= 'password: <input type="password" name="password" required="required"><br>';
	$html .= '<input type="submit" value="登录">';
	$html .= '</body></html>';
	echo $html;
});

$about_zh = urlencode('关于');
Route::get(["#^/" . $about_zh . "$#i", '#^/about$#i'], function(){
	include ROOT . '/public/about.php';
	$aboutHandler = new AboutHandler();
	return $aboutHandler->get();
});

Route::post("#^/admin/login$#i", function(){
	if (Session::get(Config::get('app.session.admin'))){
		echo 'is admin';
		return;
	}

	$username = strval($_POST['username']);
	$password = strval($_POST['password']);
	if ($username != Config::get('app.auth.admin.username') || $password != Config::get('app.auth.admin.password')){
		Session::set('admin_wrong', 'wrong');
		header('Location: /admin/login');
		return;
	}
	Session::set(Config::get('app.session.admin'), true, time()+3600);
	echo 'hi, admin';
});

Route::get("#^/admin/logout$#i", function(){
	Session::clear(Config::get('app.session.admin'));
	header('Location: /admin/login');
});

Route::setNotFound(function(){
	$response = new Response();
	$response->redirect('/404.php');
});

$request = new Request();
Route::dispatch($request->uri(), $request->method());
