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

Route::get('lists', 'TaskListController@index')->name('lists.index');
Route::post('lists', 'TaskListController@store')->name('lists.store');
Route::post('lists/seed', 'TaskListController@seed')->name('lists.seed');
Route::delete('lists/reset', 'TaskListController@reset')->name('lists.reset');
Route::delete('lists/{list}', 'TaskListController@destroy')->name('lists.destroy');

Route::prefix('lists/{list}')->group(function() {
  Route::get('tasks', 'TaskController@index')->name('tasks.index');
  Route::post('tasks', 'TaskController@store')->name('tasks.store');
  Route::post('tasks/seed', 'TaskController@seed')->name('tasks.seed');
  Route::post('tasks/complete', 'TaskController@complete')->name('tasks.complete');
  Route::post('tasks/uncomplete', 'TaskController@uncomplete')->name('tasks.uncomplete');
  Route::delete('tasks/{task}', 'TaskController@destroy')->name('tasks.destroy');
});


Route::prefix('history/{event}')->as('history.')->namespace('History')->group(function() {
  Route::get('lists', 'TaskListController@index')->name('lists.index');
  Route::post('lists', 'TaskListController@store')->name('lists.store');
  Route::post('lists/seed', 'TaskListController@seed')->name('lists.seed');
  Route::delete('lists/reset', 'TaskListController@reset')->name('lists.reset');
  Route::delete('lists/{list}', 'TaskListController@destroy')->name('lists.destroy');

  Route::prefix('lists/{list}')->group(function() {
    Route::get('tasks', 'TaskController@index')->name('tasks.index');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::post('tasks/seed', 'TaskController@seed')->name('tasks.seed');
    Route::post('tasks/complete', 'TaskController@complete')->name('tasks.complete');
    Route::delete('tasks/{task}', 'TaskController@destroy')->name('tasks.destroy');
  });

});

Route::redirect('/', '/lists', 302);
