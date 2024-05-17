<?php
use Illuminate\Support\Facades\Route;


Route::get('/candidate','PayoutController@candidateIndex')->name('payout.admin.candidate.index');
Route::post('/account/store','PayoutController@storePayoutAccount')->name('payout.admin.candidate.account.store');
Route::get('/','PayoutController@index')->name('payout.admin.index');
Route::post('/bulkEdit','PayoutController@bulkEdit')->name('payout.admin.bulkEdit');
//Route::get('/run-payout','PayoutController@runPayout')->name('payout.admin.candidate.runPayout');
