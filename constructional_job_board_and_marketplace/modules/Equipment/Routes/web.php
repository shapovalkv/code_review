<?php
use \Illuminate\Support\Facades\Route;

Route::group(['prefix'=>env('Equipment_ROUTE_PREFIX','equipment')],function(){
    Route::get('/','EquipmentController@index')->name('equipment.search'); // Search
    Route::post('/search-count','EquipmentController@EquipmentSearchCount')->name('equipment.search.count'); // Equipment count
    Route::get('/{slug}','EquipmentController@detail')->name('equipment.detail');// Detail
});

Route::group(['prefix'=>'user/'.env('Equipment_ROUTE_PREFIX','equipment'),'middleware' => ['auth','verified']],function(){
    Route::get('/','VendorEquipmentController@indexequipment')->name('equipment.vendor.index');
    Route::get('/create','VendorEquipmentController@createequipment')->name('equipment.vendor.create');
    Route::get('/edit/{id}','VendorEquipmentController@editequipment')->name('equipment.vendor.edit');
    Route::get('/del/{id}','VendorEquipmentController@deleteequipment')->name('equipment.vendor.delete');
    Route::post('/store/{id}','VendorEquipmentController@store')->name('equipment.vendor.store');
    Route::get('bulkEdit/{id}','VendorEquipmentController@bulkEditequipment')->name("equipment.vendor.bulk_edit");
    Route::get('/booking-report/bulkEdit/{id}','VendorEquipmentController@bookingReportBulkEdit')->name("equipment.vendor.booking_report.bulk_edit");
    Route::get('/recovery','VendorEquipmentController@recovery')->name('equipment.vendor.recovery');
    Route::get('/restore/{id}','VendorEquipmentController@restore')->name('equipment.vendor.restore');
    Route::get('/export','VendorEquipmentController@equipmentExport')->name('equipment.vendor.export');
});

Route::get('equipment-cat/{slug}','EquipmentController@category')->name('equipment.category');


Route::post('/equipment/buy/{id}','EquipmentController@buy')->name('equipment.buy')->middleware('auth');


Route::group(['prefix'=>'seller/equipment', 'middleware' => ['auth']],function(){
    Route::get('/all', 'EquipmentManageController@index')->name('seller.all.equipments');
    Route::get('/create', 'EquipmentManageController@create')->name('seller.equipment.create');
    Route::get('/edit/{id}', 'EquipmentManageController@edit')->name('seller.equipment.edit');
    Route::post('/store/{id}', 'EquipmentManageController@store')->name('seller.equipment.store');
    Route::get('/choose-plan/{equipment}', 'EquipmentManageController@choosePlan')->name('seller.choose.equipment.plan');
    Route::post('/choose-plan/{equipment}', 'EquipmentManageController@storePlan')->name('seller.store.equipment.plan');
    Route::post('/delete/{equipment}', 'EquipmentManageController@delete')->name('seller.equipment.delete');
    Route::post('/update/{equipment}', 'EquipmentManageController@update')->name('seller.equipment.update');
    Route::post('/bulk', 'EquipmentManageController@bulk')->name('seller.equipment.bulk');
    Route::get('/export','EquipmentManageController@equipmentExport')->name('seller.equipment.export');
});

Route::get('test',function(){
    return new \Modules\equipment\Emails\EquipmentOrderEmail(\Modules\equipment\Models\EquipmentOrder::find(1),\Modules\equipment\Models\Equipment::find(1),'author');
});
