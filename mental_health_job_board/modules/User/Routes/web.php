<?php

use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);
Route::post('email/verify', 'Auth\VerificationController@verifyCode')->name('verification.verify.post');
Route::group(['prefix'=>'user','middleware' => ['auth','verified']],function(){
    Route::post('/reloadChart','UserController@reloadChart');

    Route::get('/permanently_deleted','UserController@permanentlyDelete')->name("user.permanently.delete");
    Route::get('/profile/change-password','PasswordController@changePassword')->name("user.change_password");
    Route::post('/profile/change-password','PasswordController@changePasswordUpdate')->name("user.change_password.update");
    Route::get('/booking-history','UserController@bookingHistory')->name("user.booking_history");
    Route::get('/enquiry-report','UserController@enquiryReport')->name("vendor.enquiry_report");
    Route::get('/enquiry-report/bulkEdit/{id}','UserController@enquiryReportBulkEdit')->name("vendor.enquiry_report.bulk_edit");

    Route::post('/wishlist','UserWishListController@handleWishList')->name("user.wishList.handle");
    Route::get('/bookmark','UserWishListController@index')->name("user.wishList.index");
    Route::post('/wishlist/remove','UserWishListController@remove')->name("user.wishList.remove");

    Route::get('/following-employers','UserWishListController@followingEmployers')->name("user.following.employers");

//    Route::group(['prefix'=>'verification'],function(){
//        Route::match(['get'],'/','VerificationController@index')->name("user.verification.index");
//        Route::match(['get'],'/update','VerificationController@update')->name("user.verification.update");
//        Route::post('/store','VerificationController@store')->name("user.verification.store");
//        Route::post('/send-code-verify-phone','VerificationController@sendCodeVerifyPhone')->name("user.verification.phone.sendCode");
//        Route::post('/verify-phone','VerificationController@verifyPhone')->name("user.verification.phone.field");
//    });

    Route::group(['prefix'=>'/booking'],function(){
        Route::get('{code}/invoice','BookingController@bookingInvoice')->name('user.booking.invoice');
        Route::get('{code}/ticket','BookingController@ticket')->name('user.booking.ticket');
    });

    Route::match(['get'],'/upgrade-vendor','UserController@upgradeVendor')->name("user.upgrade_vendor");

    Route::get('wallet','WalletController@wallet')->name('user.wallet');
    Route::get('wallet/buy','WalletController@buy')->name('user.wallet.buy');
    Route::post('wallet/buyProcess','WalletController@buyProcess')->name('user.wallet.buyProcess');

    Route::group(['prefix' => 'search-params'], function () {
        Route::get('/', 'UserSearchParameterController@index')->name('user.search-params.index');
        Route::post('store', 'UserSearchParameterController@store')->name('user.search-params.store');
        Route::post('{pageSearchParameters}', 'UserSearchParameterController@update')->name('user.search-params.update');
        Route::delete('{pageSearchParameters}', 'UserSearchParameterController@delete')->name('user.search-params.delete');
    });

    Route::get('tutorial','UserController@tutorialPage')->name('user.tutorial');

    Route::get('/start-chat/{targetUser}/{job?}', 'ChatController@contact')->name('user.chat.start');
    Route::get('/chat/{conversation?}', 'ChatController@index')->name('user.chat.index');
    Route::get('/chat/{message}/download', 'ChatController@download')->name('message.download');

    Route::prefix('support')->group(static function() {
        Route::get('/','SupportController@ticketsPage')->name('user.support.index');
        Route::get('create','SupportController@create')->name('user.support.create');
        Route::post('store','SupportController@store')->name('user.support.store');
        Route::get('{ticket}','SupportController@show')->name('user.support.show');
        Route::post('{ticket}/message/{message?}','SupportController@storeMessage')->name('user.support.message.store');
        Route::get('{ticket}/{message}','SupportController@editMessage')->name('user.support.message.edit');
    });
});

Route::group(['prefix'=>'user','middleware' => ['auth','verified', 'check.role']],function(){
    Route::get('/profile','ProfileController@profile')->name("user.profile.check");
});

Route::group(['prefix'=>config('chatify.path'),'middleware'=>'auth'],function(){
    Route::get('/', 'ChatController@iframe')->name(config('chatify.path'));
    Route::post('search','ChatController@search')->name('search');
    Route::post('getContacts', 'ChatController@getContacts')->name('contacts.get');
    Route::post('idInfo', 'ChatController@idFetchData');
});

Route::group(['prefix'=>'profile'],function(){
    Route::match(['get'],'/{id}','ProfileController@profile')->name("user.profile");
    Route::match(['get'],'/{id}/reviews','ProfileController@allReviews')->name("user.profile.reviews");
    Route::match(['get'],'/{id}/services','ProfileController@allServices')->name("user.profile.services");
});

//Newsletter
Route::post('newsletter/subscribe','UserController@subscribe')->name('newsletter.subscribe');


Route::get('/user/my-subscription','PlanController@myPlan')->name('user.subscription')->middleware(['auth', 'verified']);
Route::get('/subscription','PlanController@index')->name('subscription')->middleware(['auth', 'verified']);
Route::get('/user/plan/buy/{id}','PlanController@buy')->name('user.plan.buy')->middleware(['auth', 'verified']);


//Contact
Route::group(['middleware'=>'auth'],function() {
    Route::get('/user/my-contact', 'ContactController@myContact')->name("user.my-contact");
});

Route::match(['get'],'/upgrade-company','UserController@upgradeCompany')->name("user.upgrade_company");
