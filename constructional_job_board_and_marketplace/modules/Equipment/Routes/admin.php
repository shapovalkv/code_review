<?php

use \Illuminate\Support\Facades\Route;

Route::get('/', 'EquipmentController@index')->name('equipment.admin.index');
Route::get('/create', 'EquipmentController@create')->name('equipment.admin.create');
Route::get('/edit/{id}', 'EquipmentController@edit')->name('equipment.admin.edit');
Route::post('/store/{id}', 'EquipmentController@store')->name('equipment.admin.store');
Route::post('/bulkEdit', 'EquipmentController@bulkEdit')->name('equipment.admin.bulkEdit');
Route::get('/recovery', 'EquipmentController@recovery')->name('equipment.admin.recovery');
Route::get('/getForSelect2', 'EquipmentController@getForSelect2')->name('equipment.admin.getForSelect2');

Route::group(['prefix' => 'attribute'], function () {
    Route::get('/', 'AttributeController@index')->name('equipment.admin.attribute.index');
    Route::get('edit/{id}', 'AttributeController@edit')->name('equipment.admin.attribute.edit');
    Route::post('store/{id}', 'AttributeController@store')->name('equipment.admin.attribute.store');
    Route::post('/editAttrBulk', 'AttributeController@editAttrBulk')->name('equipment.admin.attribute.editAttrBulk');

    Route::get('getForSelect2', 'AttributeController@getForSelect2')->name('equipment.admin.attribute.term.getForSelect2');
});

Route::group(['prefix' => 'category'], function () {
    Route::get('/', 'CategoryController@index')->name('equipment.admin.category.index');
    Route::get('/edit/{id}', 'CategoryController@edit')->name('equipment.admin.category.edit');
    Route::post('/store/{id}', 'CategoryController@store')->name('equipment.admin.category.store');
    Route::post('/bulkEdit', 'CategoryController@bulkEdit')->name('equipment.admin.category.bulkEdit');
    Route::get('/getForSelect2', 'CategoryController@getForSelect2')->name('equipment.admin.category.getForSelect2');
});
Route::group(['prefix' => 'category_type'], function () {
    Route::get('/', 'CategoryTypeController@index')->name('equipment.admin.category_type.index');
    Route::get('/edit/{id}', 'CategoryTypeController@edit')->name('equipment.admin.category_type.edit');
    Route::post('/store/{id}', 'CategoryTypeController@store')->name('equipment.admin.category_type.store');
    Route::post('/bulkEdit', 'CategoryTypeController@bulkEdit')->name('equipment.admin.category_type.bulkEdit');
    Route::get('/getForSelect2', 'CategoryTypeController@getForSelect2')->name('equipment.admin.category_type.getForSelect2');
});
