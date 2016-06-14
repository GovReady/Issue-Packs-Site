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

Route::any('{slug}', function ($slug) {
  return view('welcome');
})->where('slug', '^(?!(api)/)(.*)$');

Route::group(['prefix' => 'api', 'middleware' => ['web']], function () {
  Route::post('login', 'UserController@login');
  Route::get('logout', 'UserController@logout');
  Route::get('my-packs', 'PackController@getMyPacks');
  Route::post('packs', 'PackController@createPack');
  Route::get('packs/search', 'PackController@searchPacks');
  Route::delete('packs/{id}', 'PackController@deletePack');
  Route::post('packs/{id}/copy', 'PackController@copyPack');
  Route::post('packs/{id}/publish', 'PackController@publishPack');
  Route::post('packs/{id}/sync', 'PackController@saveSync');

  Route::group(['prefix' => 'connections'], function () {
    Route::get('/', 'ConnectionController@getConnections');
    Route::post('/', 'ConnectionController@saveConnection');
    Route::delete('/{id}', 'ConnectionController@deleteConnection');
  });
});

