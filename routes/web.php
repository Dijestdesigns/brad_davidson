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
        Route::get('clients/me', 'ClientsController@myProfile')->name('clients.myprofile');
        Route::post('clients/me/update', 'ClientsController@updateProfile')->name('clients.myprofile.update');
        Route::resources(['clients' => 'ClientsController']);
    });

    Route::group(['namespace' => 'Training'], function () {
        Route::post('training/client/store', 'TrainingController@clientStore')->name('training.client.store');
        Route::post('training/client/{userId}/info/create', 'TrainingController@clientInfoCreate')->name('training.client.info.create');
        Route::post('training/client/{userId}/info/update', 'TrainingController@clientInfoUpdate')->name('training.client.info.update');
        Route::get('training/client/{userId}/history', 'TrainingController@clientHistory')->name('training.client.history');
        Route::get('training/client/index', 'TrainingController@clientIndex')->name('training.client.index');
        Route::resources(['training' => 'TrainingController']);
    });

    Route::group(['namespace' => 'Constants'], function () {
        Route::resources(['constants' => 'ConstantsController']);
    });

    Route::group(['namespace' => 'Chat'], function () {
        Route::resources(['chat' => 'ChatController']);
        Route::get('chat/{userId}/individual', 'ChatController@individual')->name('chat.individual');
        Route::get('chat/{groupId}/group', 'ChatController@group')->name('chat.group');

        Route::post('chat/individual', 'ChatController@individualPost')->name('chat.individual.post');
        Route::post('chat/room', 'ChatController@groupPost')->name('chat.room.post');

        Route::post('chat/markAsRead/{chatId}', 'ChatController@markAsRead')->name('chat.room.markAsRead');

        Route::delete('chat/room/{chatRoomId}/destroyUser', 'ChatController@destroyUser')->name('chat.room.destroyUser');
    });

    Route::group(['namespace' => 'Calendar'], function () {
        Route::resources(['calendar' => 'CalendarController']);
        Route::post('calendar/update', 'CalendarController@update')->name('calendar.update');
    });

    Route::group(['namespace' => 'Notes'], function () {
        Route::resources(['notes' => 'NoteController']);
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
