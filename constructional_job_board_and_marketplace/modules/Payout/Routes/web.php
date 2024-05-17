<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>'auth'],function() {
    Route::get('user/payout/', 'PayoutController@candidateIndex')->name('payout.candidate.index');
    Route::post('payout/account/store', 'PayoutController@storePayoutAccount')->name('payout.candidate.account.store');
});
