<?php

Route::get('/', 'FrontendController@Index');
Route::get('login', 'FrontendController@Login');
Route::get('logout', 'FrontendController@Logout');
Route::group(['prefix' => 'view'], function() {
	Route::get('markdown', 'FrontendController@DisplayMarkdown');
});