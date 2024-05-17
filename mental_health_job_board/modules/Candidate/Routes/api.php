<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'candidate'], function () {
    Route::post('/', 'Api\ProfileController@updateCandidateAttribute')->name('candidates.api.update');
});
