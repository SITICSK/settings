<?php

use \Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api/v1', 'namespace' => 'Sitic\Settings\Http\Controllers\V1', 'middleware' => 'auth'], function () {
    /* Settings */
    Route::post('/settings', 'SettingController@store');
    Route::get('/settings', 'SettingController@index');
    Route::get('/settings/{id}', 'SettingController@show');
    Route::put('/settings/{id}', 'SettingController@update');
    Route::delete('/settings/{id}', 'SettingController@destroy');
    Route::post('/settings/{id}/restore', 'SettingController@restore');
    Route::post('/settings/{id}/forceDelete', 'SettingController@forceDelete');

    /* Setting Items */
    Route::post('/settingItems', 'SettingItemController@store');
    Route::get('/settingItems', 'SettingItemController@index');
    Route::get('/settingItems/{id}', 'SettingItemController@show');
    Route::put('/settingItems/{id}', 'SettingItemController@update');
    Route::delete('/settingItems/{id}', 'SettingItemController@destroy');
    Route::post('/settingItems/{id}/restore', 'SettingItemController@restore');
    Route::post('/settingItems/{id}/forceDelete', 'SettingItemController@forceDelete');
});

