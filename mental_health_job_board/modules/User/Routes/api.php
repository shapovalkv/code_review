<?php

use \Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'notice'],function(){
    Route::post('dashboard/{dashboard_notice}/read','Api\DashboardNoticeController@readNotice')->name('user.notice.dashboard.read');
});
