<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>config('companies.companies_route_prefix')],function(){
    Route::get('/'.config('companies.companies_category_route_prefix').'/{slug}', 'CompanyController@index')->name('companies.category.index');
    Route::get('/','CompanyController@index')->name('companies.index');// Companies Page
    Route::get('/{slug}','CompanyController@detail')->name('companies.detail');// Companies Detail

    Route::post('/contact/store','CompanyController@storeContact')->name("company.contact.store");
});

Route::group(['prefix'=> 'user/company', 'middleware'=> ['auth','verified']],function() {
    Route::get('profile', 'ManageCompanyController@companyProfile')->name("user.company.profile");
    Route::post('update', 'ManageCompanyController@companyUpdate')->name("user.company.update");
    Route::get('staff', 'ManageCompanyStaffController@index')->name('user.company.staff');
    Route::get('staff/new', 'ManageCompanyStaffController@edit')->name('user.company.staff.create');
    Route::get('staff/{user}', 'ManageCompanyStaffController@edit')->name('user.company.staff.edit');
    Route::post('staff/{user?}', 'ManageCompanyStaffController@store')->name('user.company.staff.store');
    Route::post('staff/{trashed_user}/delete', 'ManageCompanyStaffController@delete')->name('user.company.staff.delete');
    Route::post('staff/{trashed_user}/enable', 'ManageCompanyStaffController@enable')->name('user.company.staff.enable');
    Route::post('staff/{trashed_user}/disable', 'ManageCompanyStaffController@disable')->name('user.company.staff.disable');
});
