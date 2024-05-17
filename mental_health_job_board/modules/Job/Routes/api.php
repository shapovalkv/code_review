<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'job'], function () {
    Route::post('{job?}', 'Api\JobController@updateJobAttribute')->name('job.api.update');
});
