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

Route::get('/', 'DashboardController@index')->name('dashboard');

$middlewares = ['auth'];

Route::middleware($middlewares)->group(function() {

    Route::group(['namespace' => 'Inventory'], function () {
        Route::resources(['inventory' => 'InventoryController']);
        Route::post('inventory/change/quantity/{id}', 'InventoryController@changeQuantity')->name('inventory.change.quantity');
        Route::post('inventory/moveto/folder/{id}', 'InventoryController@moveToFolder')->name('inventory.moveto.folder');
    });

    Route::group(['namespace' => 'Tags'], function () {
        Route::resources(['tags' => 'TagsController']);
    });

    Route::group(['namespace' => 'Clients'], function () {
        Route::resources(['clients' => 'ClientsController']);
        Route::get('clients/{id}/me', 'ClientsController@myProfile')->name('clients.myprofile');
    });

    Route::group(['namespace' => 'Training'], function () {
        Route::resources(['training' => 'TrainingController']);
    });

    Route::group(['namespace' => 'Chat'], function () {
        Route::resources(['chat' => 'ChatController']);
    });

    Route::group(['namespace' => 'Calendar'], function () {
        Route::resources(['calendar' => 'CalendarController']);
        Route::post('calendar/update', 'CalendarController@update')->name('calendar.update');
    });

    Route::group(['namespace' => 'Diary'], function () {
        Route::resources(['diary' => 'DiaryController']);
    });

    Route::group(['namespace' => 'Supplements'], function () {
        Route::resources(['supplements' => 'SupplementsController']);
        Route::get('supplements/{userId}/{date}/edit', 'SupplementsController@edit')->name('supplements.edit');
        Route::post('supplements/{userId}/{date}/update', 'SupplementsController@update')->name('supplements.update');
        Route::delete('supplements/{userId}/{date}/destroy', 'SupplementsController@destroy')->name('supplements.destroy');
    });

    Route::group(['namespace' => 'StockLevels'], function () {
        Route::resources(['stock_levels' => 'StockLevelsController']);
    });

    Route::group(['namespace' => 'StockValues'], function () {
        Route::resources(['stock_values' => 'StockValuesController']);
    });

    Route::group(['namespace' => 'Trash'], function () {
        Route::resources(['trash' => 'TrashController']);
    });

    Route::group(['namespace' => 'Logs'], function () {
        Route::resources(['logs' => 'LogsController']);
    });

    Route::group(['namespace' => 'Roles', 'middleware' => ['permission:roles_access']], function () {
        Route::resources(['roles' => 'RoleController']);
    });

    Route::group(['namespace' => 'Permissions', 'middleware' => ['permission:permissions_access']], function () {
        Route::resources(['permissions' => 'PermissionController']);
    });

    Route::get('/storage/link', function () {
        Artisan::call('storage:link');

        dd(Artisan::output());
    });
});
