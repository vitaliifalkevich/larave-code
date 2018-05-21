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
/**
 * Пользовательская часть маршрутов
 */
Route::get('/', 'IndexController@showIndex')->name('index');

Route::get('/category/{category}', 'CategoryController@category')->name('category');

Route::match(['get','post'],'/tovar/{tovarLink}', ['uses'=>'TovarController@tovar','as'=>'tovar']);

Route::match(['get', 'post'],'/checkout', 'CheckOutController@checkout')->name('checkout');

Route::match(['get', 'post'],'/contacts', ['uses'=>'ContactController@contact','as'=>'contacts']);

Route::get('/search/{tovarToFind}', ['uses'=>'SearchController@results','as'=>'search']);

Auth::routes();
/**
 * Група маршрутов админа
 */
Route::group(['prefix' => '/administrator'], function () {
    Route::get('/', 'HomeController@index')->name('administrator');

    //добавление материала
    Route::get('/add', 'AdminController@index')->name('adminAddPage');
    Route::post('/add', 'AdminController@add');

    //редактирование материала
    Route::get('/edit/{tovarId}', 'AdminController@indexEdit')->name('adminEditPage');
    Route::post('/edit/{tovarId}', 'AdminController@edit');

   Route::delete('/', 'HomeController@index');

   Route::match(['get','post'],'/view/order/{orderId}', 'ViewOrdersController@index')->name('viewOrders');
});



