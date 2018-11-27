<?php


use Illuminate\Http\Request;
use Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


function getResponse($status, $statusText, $body){
	$response = new Response();
	$response->setStatusCode($status, $statusText);
	$response->setContent($body);

	return $response;
}
Route::post('/login', function (Request $request) {
	$userLogin = $request['login'];
	$userPassword = $request['password'];

	$apiResult = DB::table('users')->get(['login', 'password'])->where('login', '=', $userLogin);

	if (count($apiResult) === 0) {
		return getResponse(401, 'NOT OK', [
			'error' => 'unknown user'
		]);
	}

	$adminLogin = $apiResult[0] -> login;
	$adminPassword = $apiResult[0] -> password;

	if ($adminLogin === $userLogin && $adminPassword === $userPassword) {
		return getResponse(201, 'OK', [
			'login' => $adminLogin,
			'password' => $adminPassword,
			'token' => 'gklaGSWganasgga12saf'
		]);
	}else{
		return getResponse(401, 'NOT OK', [
			'error' => 'not correct password'
		]);
	};
});


Route::get('/tasks', function(){
	$tasks = DB::table('tasks')->get(['id', 'name', 'category', 'deadline']);
	return $tasks;
});

Route::get('/tasks/{id}', function($id){
	$tasks = DB::table('tasks')->get(['id', 'name', 'category', 'deadline'])->where('id', '=', $id);
	return $tasks;
});

Route::get('/tasks/{id}', function($id){
	$tasks = DB::table('tasks')->get(['id', 'name', 'description', 'category', 'deadline', 'status'])->where('id', '=', $id);
	return $tasks;
});

Route::get('/categories', function(){
	$cat = DB::table('categories')->get(['id', 'category']);
	return $cat;
});

Route::get('/categories', function(){
	$cat = DB::table('categories')->get(['id', 'category']);
	return $cat;
});

Route::get('/complete', function(){
	$tasks = DB::table('tasks')->get(['id', 'name', 'category', 'deadline', 'status'])->where('status', '=', 100);
	return $tasks;
});

Route::post('/tasks', function(Request $request){
	DB::table('tasks')->insert(array(
		'name'        => $request['name'],
		'category'    => $request['category'],
		'deadline'    => $request['deadline'],
		'description' => $request['description'],
		'status'      => $request['status']
	));
});

Route::put('/tasks', function(Request $request){
	DB::table('tasks')->where('id', '=', $request['id'])->update(array(
		'name'        => $request['name'],
		'category'    => $request['category'],
		'deadline'    => $request['deadline'],
		'description' => $request['description'],
		'status'      => $request['status']
	));
});


Route::patch('/tasks/{task}', function($id){
	$task = DB::table('tasks')->where('id', '=', $id)->update(array('status'=> 100));
});

Route::patch('/complete/{task}', function($id){
	$task = DB::table('tasks')->where('id', '=', $id)->update(array('status'=> 0));
});


