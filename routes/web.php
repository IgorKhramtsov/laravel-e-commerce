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

Route::get('/', "ProductController@index")->name("products");
Route::get('admin', "AdminController@index")->name("admin");
Route::resource('product', "ProductController");
Route::resource('review', "ReviewController");
Auth::routes();

Route::get('products', "ProductController@index");

Route::get("cart", "CartController@index")->name("cart");
Route::get("order/success", "OrderController@success")->name("order_success");
Route::get("order/fail", "OrderController@fail")->name("order_fail");


Route::post("cart/add", "CartController@add");
Route::get("api_cart", "CartController@api_index");
Route::post("api_cart/update", "CartController@api_update");
Route::post("api_cart/remove", "CartController@remove");
Route::post("api_cart/order", "CartController@create_order");
Route::post("api_cart/saveOrder", "CartController@save_order");