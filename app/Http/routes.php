<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['as' => 'page.home', 'uses' => 'PageController@getIndex']);
    Route::get('/app', ['as' => 'app.home', 'uses' => 'AppController@getIndex']);
});

Route::post('/auth', ['uses' => 'Auth\AuthController@login']);
Route::get('logout', ['uses' => 'Auth\AuthController@logout']);
Route::group(['middleware' => ['auth:api'], 'prefix' => 'api'], function() {
    Route::group(['prefix' => 'groups'], function() {
        Route::get('/', ['as' => 'api.groups.list', 'uses' => 'Api\GroupController@getGroupList']);
        Route::get('/own', ['as' => 'api.groups.list.own', 'uses' => 'Api\GroupController@getOwnGroupList']);
        Route::post('/', ['as' => 'api.groups.add', 'uses' => 'Api\GroupController@postAddGroup']);
    });
});
Route::get('/template/{path}', function ($path) {
    $path = str_replace("/", ".", $path);

    $viewName = 'angular.partials.' . $path;

    if (view()->exists($viewName)) {
        return view($viewName);
    } else {
        throw new Exception('View ' . $viewName . ' was requested but does not exist');
    }
});