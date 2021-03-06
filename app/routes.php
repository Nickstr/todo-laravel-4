<?php
/**
 * TIPS
 * You see the complete list of your route with : php artisan routes
 */


/**
 * Homepage, contain all our todo interface, require to be logged for access
 */
Route::get('/', ['before' => 'auth', function()
{
	return View::make('pages.homepage');
}]);

/**
 * Resource Todo controller, will handle all change to our todo-list via ajax
 * this controller only accept AJAX request cf: $this->beforeFilter('ajax'); in __construct
 * You can set the filter here too, take a look below to the 'background-loader' route
 */
Route::resource('todo', 'TodoController', ['except' => ['show', 'store', 'edit']]);
Route::resource('players', 'PlayerController');

/**
 * RestFull Setting controller
 * Auto detect all methods
 */
Route::controller('settings', 'SettingController');

/**
 * Create a GET route for the ajax background gallery loader
 * Add filter to restrict the access to ajax request only
 */
Route::get('background-loader', 'SettingController@backgroundList')->before('ajax');


// Login form
Route::get('login', ['as' => 'login', function(){
	return View::make('pages.login');
}]);

// Login attempt
Route::post('login', ['as' => 'login.attempt', 'before' => 'csrf', function() {
	if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
	{
		// redirect to the intended url before getting cough by the auth filter
		return Redirect::intended('/');
	}
	Session::flash('error','Wasnt it.');
	return Redirect::route('login')->withInput(['except' => 'password']);
}]);

// Logout
Route::get('logout', ['as' => 'logout', function(){
	Auth::logout();
	return Redirect::route('login');
}]);
