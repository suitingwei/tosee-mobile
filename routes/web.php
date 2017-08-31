<?php

Route::get('/mp', 'MPController@index');
Route::get('/mp/share/play/{id}', 'MPController@info');
Route::get('/mp/play/users/{id}', 'MPController@users');
Route::post('/mp/parise', 'MPController@praise');
Route::get('/mp/groupshoots/{id}', 'MPController@groupshoot');
Route::get('/mp/video/{id}', 'MPController@video');
Route::get('/mp/video_next/{id}', 'MPController@video_next');

Route::get('/', 'HomeController@home');
Route::get('en', 'HomeController@enhome');
Route::get('robot/{id}', 'HomeController@robot');
Route::get('app/download', 'HomeController@download');
Route::get('s/{id}/{sign}', 'HomeController@shareShow');
Route::get('/agreement', 'HomeController@agreement');

Route::get('/groupshoot', 'IsaController@jumpIos');

Route::get('/sss','IsaController@sss');