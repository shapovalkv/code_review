<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'marketplace'], function () {
    Route::post('{marketplace?}', 'Api\MarketplaceController@updateMarketplaceAttribute')->name('marketplace.api.update');
});
