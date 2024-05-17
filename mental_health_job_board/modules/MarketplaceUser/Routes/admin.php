<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
use Illuminate\Support\Facades\Route;

Route::get('/','MarketplaceUserController@index')->name('marketplace_user.admin.index');
Route::get('/export','MarketplaceUserController@export')->name('marketplace_user.admin.export');
Route::get('/create','MarketplaceUserController@create')->name('marketplace_user.admin.create');
Route::get('/edit/{id}', 'MarketplaceUserController@edit')->name('marketplace_user.admin.edit');
Route::post('/bulkEdit','MarketplaceUserController@bulkEdit')->name('marketplace_user.admin.bulkEdit');
Route::post('/store/{id}','MarketplaceUserController@store')->name('marketplace_user.admin.store');

