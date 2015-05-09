<?php

define('IN_SYSTEM', true);
include 'start.php';

Route::get("#^/c(?:/([^/&\?]*))?$#i", function($collection_name){
	$collection_handler = new CollectionHandler();
	$collection_handler->get($collection_name);
});

// 关注的合集
Route::get("#^/follow/c$#i", function(){
	$follow_collection_handler = new FollowCollectionHandler();
	$follow_collection_handler->get();
});
Route::post("#^/follow/c$#i", function(){
	$follow_collection_handler = new FollowCollectionHandler();
	$follow_collection_handler->post();
});


// admin
Route::get("#^/admin/login$#i", function(){
	header('Location: /404.php');
});

Route::post("#^/admin/login$#i", function(){
	header('Location: /404.php');
});

Route::get("#^/admin/logout$#i", function(){
	// Session::clear(Config::get('app.session.admin'));
	// header('Location: /admin/login');
	header('Location: /404.php');
});

Route::setNotFound(function(){
	$response = new Response();
	$response->redirect('/404.php');
});

$request = new Request();
Route::dispatch($request->uri(), $request->method());
