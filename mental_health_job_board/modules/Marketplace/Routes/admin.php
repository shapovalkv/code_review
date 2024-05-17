<?php

use \Illuminate\Support\Facades\Route;

Route::get('/', 'MarketplaceController@index')->name('marketplace.admin.index');
Route::get('/create', 'MarketplaceController@create')->name('marketplace.admin.create');
Route::get('/edit/{id}', 'MarketplaceController@edit')->name('marketplace.admin.edit');
Route::post('/store/{id}', 'MarketplaceController@store')->name('marketplace.admin.store');
Route::post('/bulkEdit', 'MarketplaceController@bulkEdit')->name('marketplace.admin.bulkEdit');
Route::get('/recovery', 'MarketplaceController@recovery')->name('marketplace.admin.recovery');
Route::get('/getForSelect2', 'MarketplaceController@getForSelect2')->name('marketplace.admin.getForSelect2');

Route::group(['prefix' => 'attribute'], function () {
    Route::get('/', 'AttributeController@index')->name('marketplace.admin.attribute.index');
    Route::get('edit/{id}', 'AttributeController@edit')->name('marketplace.admin.attribute.edit');
    Route::post('store/{id}', 'AttributeController@store')->name('marketplace.admin.attribute.store');
    Route::post('/editAttrBulk', 'AttributeController@editAttrBulk')->name('marketplace.admin.attribute.editAttrBulk');

    Route::get('getForSelect2', 'AttributeController@getForSelect2')->name('marketplace.admin.attribute.term.getForSelect2');
});

Route::group(['prefix' => 'category'], function () {
    Route::get('/', 'CategoryController@index')->name('marketplace.admin.category.index');
    Route::get('/edit/{id}', 'CategoryController@edit')->name('marketplace.admin.category.edit');
    Route::post('/store/{id}', 'CategoryController@store')->name('marketplace.admin.category.store');
    Route::post('/bulkEdit', 'CategoryController@bulkEdit')->name('marketplace.admin.category.bulkEdit');
    Route::get('/getForSelect2', 'CategoryController@getForSelect2')->name('marketplace.admin.category.getForSelect2');
});
Route::group(['prefix' => 'category_type'], function () {
    Route::get('/', 'CategoryTypeController@index')->name('marketplace.admin.category_type.index');
    Route::get('/edit/{id}', 'CategoryTypeController@edit')->name('marketplace.admin.category_type.edit');
    Route::post('/store/{id}', 'CategoryTypeController@store')->name('marketplace.admin.category_type.store');
    Route::post('/bulkEdit', 'CategoryTypeController@bulkEdit')->name('marketplace.admin.category_type.bulkEdit');
    Route::get('/getForSelect2', 'CategoryTypeController@getForSelect2')->name('marketplace.admin.category_type.getForSelect2');
});
