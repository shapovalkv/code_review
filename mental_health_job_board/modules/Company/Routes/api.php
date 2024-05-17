<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'company'], function () {
    Route::post('/', 'Api\ManageCompanyController@updateCompanyAttribute')->name('companies.api.update');
    Route::delete('office/{company_office}', 'Api\ManageCompanyController@deleteCompanyOffice')->name('companies.api.office.delete');
});
