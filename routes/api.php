<?php

Route::resource('orders', 'Orders\OrderController');
Route::resource('payment-methods', 'PaymentMethods\PaymentMethodController');
Route::resource('categories', 'Categories\CategoryController');
Route::resource('products', 'Products\ProductController');
Route::resource('addresses', 'Addresses\AddressController');
Route::resource('countries', 'Countries\CountryController');
Route::get('addresses/{address}/shipping', 'Addresses\AddressShippingController@action');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Auth\RegisterController@create');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('me', 'Auth\MeController@me');
});

Route::resource('cart', 'Cart\CartController', [
    "parameters" => [
        "cart" => "productVariation"
    ]
]);
