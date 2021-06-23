<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("products", "ProductController@json_index");
Route::get("reviews/{product_id}", "ReviewController@index_published");

Route::middleware('auth:api')->group(function () {
    Route::put("products/{id}", "ProductController@update");
    Route::post("products", "ProductController@store");

    Route::get("reviews", "ReviewController@index_unpublished");
    Route::post("reviews", "ReviewController@store");
    Route::post("reviews/{product_id}", "ReviewController@publish");
    Route::delete("reviews/{product_id}", "ReviewController@destroy");

    Route::get("orders", "OrderController@index");
    Route::get("orders/{id}", "OrderController@get_items");
});

