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
Route::group(['prefix' => 'api'], function() {
    Route::group(['middleware' => ['auth:api']], function() {
        Route::group(['prefix' => 'groups'], function() {
            Route::get('/', ['as' => 'api.groups.list', 'uses' => 'Api\GroupController@getGroupList']);
            Route::get('/own', ['as' => 'api.groups.list.own', 'uses' => 'Api\GroupController@getOwnGroupList']);
            Route::post('/', ['as' => 'api.groups.add', 'uses' => 'Api\GroupController@postAddGroup']);
            Route::put('/{group}', ['as' => 'api.groups.edit', 'uses' => 'Api\GroupController@putEditGroup']);
            Route::delete('/{group}', ['as' => 'api.groups.delete', 'uses' => 'Api\GroupController@deleteGroup']);

            Route::get('/{group}/draws', ['as' => 'api.groups.draws.list', 'uses' => 'Api\GroupController@listGroupDraws']);
        });
    });

    Route::group(['prefix' => 'hipchat'], function() {
        Route::get('/capabilities', ['as' => 'api.hipchat.capabilities', 'uses' => 'Api\HipchatController@getCapabilities']);
        Route::post('/install', ['as' => 'api.hipchat.install', 'uses' => 'Api\HipchatController@postInstall']);
        Route::post('/uninstall', ['as' => 'api.hipchat.uninstall', 'uses' => 'Api\HipchatController@postUninstall']);

        Route::post('/workout-done', ['as' => 'api.hipchat.command.workoutdone', 'uses' => 'Api\HipchatController@postWorkoutDoneCommand']);
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