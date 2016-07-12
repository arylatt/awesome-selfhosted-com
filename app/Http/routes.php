<?php

$proxy['url'] = getenv('PROXY_URL');
$proxy['protocol'] = getenv('PROXY_PROTOCOL');
if(!empty($proxy['url'])) {
	URL::forceRootUrl($proxy['url']);
}
if(!empty($proxy['protocol'])) {
	URL::forceSchema($proxy['protocol']);
}

Route::get('/', 'FrontendController@Index');
Route::group(['prefix' => 'view'], function() {
	Route::get('/', function() {
		redirect('/');
	});
	Route::get('markdown', 'FrontendController@DisplayMarkdown');
});
Route::get('submit', 'FrontendController@Submit');
Route::get('team', 'FrontendController@Team');

Route::get('login', 'FrontendController@Login');
Route::get('logout', 'FrontendController@Logout');