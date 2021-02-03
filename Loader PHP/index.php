<?php  

include('route.php');

Route::add('/login/([a-zA-Z0-9]*)/([a-zA-Z0-9]*)/([a-zA-Z0-9]*)',function($user,$pass,$hwid){
	session_start();
	if(empty($user) || empty($pass) || empty($hwid)) return require __DIR__ . '/404/index.html';  
    require __DIR__ . '/main/login.php';
});
Route::add("/class",function(){
	require __DIR__ . '/main/class.php';
});

Route::add("/admin/update",function(){
	require __DIR__ . '/main/update.php';
},'post');

Route::add('/register/([a-zA-Z0-9]*)/([a-zA-Z0-9]*)/([a-zA-Z0-9]*)',function($user,$pass,$hwid){
	session_start();
	if(empty($user) || empty($pass) || empty($hwid)) return require __DIR__ . '/404/index.html';  
    require __DIR__ . '/main/register.php';
});


Route::run('/');