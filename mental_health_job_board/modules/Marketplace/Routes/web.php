<?php
use \Illuminate\Support\Facades\Route;

Route::group(['prefix'=>env('Marketplace_ROUTE_PREFIX','marketplace')],function(){
    Route::get('/','MarketplaceController@index')->name('marketplace.search'); // Search
    Route::post('/search-count','MarketplaceController@MarketplaceSearchCount')->name('marketplace.search.count'); // Marketplace count
    Route::get('/{slug}','MarketplaceController@detail')->name('marketplace.detail');// Detail
});

Route::group(['prefix'=>'user/'.env('Marketplace_ROUTE_PREFIX','marketplace'),'middleware' => ['auth','verified']],function(){
    Route::get('/','VendorMarketplaceController@indexMarketplace')->name('marketplace.vendor.index');
    Route::get('/create','VendorMarketplaceController@createMarketplace')->name('marketplace.vendor.create');
    Route::get('/edit/{id}','VendorMarketplaceController@editMarketplace')->name('marketplace.vendor.edit');
    Route::get('/del/{id}','VendorMarketplaceController@deleteMarketplace')->name('marketplace.vendor.delete');
    Route::post('/store/{id}','VendorMarketplaceController@store')->name('marketplace.vendor.store');
    Route::get('bulkEdit/{id}','VendorMarketplaceController@bulkEditMarketplace')->name("marketplace.vendor.bulk_edit");
    Route::get('/booking-report/bulkEdit/{id}','VendorMarketplaceController@bookingReportBulkEdit')->name("marketplace.vendor.booking_report.bulk_edit");
    Route::get('/recovery','VendorMarketplaceController@recovery')->name('marketplace.vendor.recovery');
    Route::get('/restore/{id}','VendorMarketplaceController@restore')->name('marketplace.vendor.restore');
    Route::get('/export','VendorMarketplaceController@MarketplaceExport')->name('marketplace.vendor.export');
});

Route::get('marketplace-cat/{slug}','MarketplaceController@category')->name('marketplace.category');


Route::post('/marketplace/buy/{id}','MarketplaceController@buy')->name('marketplace.buy')->middleware('auth');


Route::group(['prefix'=>'seller/marketplace', 'middleware' => ['auth']],function(){
    Route::get('/all', 'MarketplaceManageController@index')->name('seller.all.marketplaces');
    Route::get('/create', 'MarketplaceManageController@create')->name('seller.marketplace.create');
    Route::get('/edit/{id}', 'MarketplaceManageController@edit')->name('seller.marketplace.edit');
    Route::post('/store', 'MarketplaceManageController@store')->name('seller.marketplace.store');
    Route::get('/choose-plan/{marketplace}', 'MarketplaceManageController@choosePlan')->name('seller.choose.marketplace.plan');
    Route::post('/choose-plan/{marketplace}', 'MarketplaceManageController@storePlan')->name('seller.store.marketplace.plan');
    Route::post('/delete/{marketplace}', 'MarketplaceManageController@delete')->name('seller.marketplace.delete');
    Route::post('/update/{marketplace}', 'MarketplaceManageController@update')->name('seller.marketplace.update');
    Route::post('/bulk', 'MarketplaceManageController@bulk')->name('seller.marketplace.bulk');
    Route::get('/export','MarketplaceManageController@MarketplaceExport')->name('seller.marketplace.export');
});
