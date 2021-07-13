<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');

Route::group(['prefix' => 'csv'], function () {
  Route::get('/panel_1', 'APanelController@downloadCsv');
  Route::get('/panel_2', 'BPanelController@downloadCsv');
  Route::get('/by_day_power', 'DailyController@downloadCsv');
});
