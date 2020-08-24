<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes(['register' => false, 'reset' => false]);

$middlewares = ['auth'];

Route::middleware($middlewares)->group(function() {

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::group(['namespace' => 'Items'], function () {
        Route::resources(['items' => 'ItemsController']);
        Route::post('items/change/quantity/{id}', 'ItemsController@changeQuantity')->name('items.change.quantity');
        Route::post('items/moveto/folder/{id}', 'ItemsController@moveToFolder')->name('items.moveto.folder');
    });

    Route::group(['namespace' => 'Tags'], function () {
        Route::resources(['tags' => 'TagsController']);
    });

    Route::group(['namespace' => 'Folders'], function () {
        Route::resources(['folders' => 'FoldersController']);
    });

    Route::group(['namespace' => 'StockLevels'], function () {
        Route::resources(['stock_levels' => 'StockLevelsController']);
    });

    Route::group(['namespace' => 'StockValues'], function () {
        Route::resources(['stock_values' => 'StockValuesController']);
    });
});
