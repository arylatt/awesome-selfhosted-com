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
Route::get('login', 'FrontendController@Login');
Route::get('logout', 'FrontendController@Logout');
Route::group(['prefix' => 'view'], function() {
	Route::get('markdown', 'FrontendController@DisplayMarkdown');
});