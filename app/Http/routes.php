<?php



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('users', 'UserController@all');

Route::get('facebook-login', function()
{
    return View::make('facebook_login');
});

Route::get('do-login-facebook', 'Auth\AuthController@loginWithFacebook');

Route::get('return-facebook-login', 'Auth\AuthController@returnOfFacebookLogin');