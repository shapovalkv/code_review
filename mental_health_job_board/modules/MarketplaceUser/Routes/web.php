<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'prefix'=>config('marketplace_user.marketplace_user_route_prefix')],function(){
    Route::get('/','MarketplaceUserController@index')->name('marketplace_user.index');// MarketplaceUsers Page
    Route::get('/{marketplace_user}','MarketplaceUserController@detail')->name('marketplace_user.detail');// Detail
});

Route::group(['middleware' => ['auth','verified']],function() {
    Route::get('/user/marketplace-user/profile','ProfileController@index')->name('user.marketplace_user.index');
    Route::post('/user/marketplace-user/profile/store','ProfileController@store')->name('user.marketplace_user.store');
});

