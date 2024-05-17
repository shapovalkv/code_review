<?php
use \Illuminate\Support\Facades\Route;

Route::get('/getForSelect2', 'UserController@getForSelect2')->name('user.admin.getForSelect2');
Route::get('/', 'UserController@index')->name('user.admin.index');
Route::get('/create', 'UserController@create')->name('user.admin.create');
Route::get('/edit/{id}', 'UserController@edit')->name('user.admin.detail');
Route::post('/store/{id}', 'UserController@store')->name('user.admin.store');
Route::post('/bulkEdit', 'UserController@bulkEdit')->name('user.admin.bulkEdit');
Route::get('/password/{id}','UserController@password')->name('user.admin.password');
Route::post('/changepass/{id}','UserController@changepass')->name('user.admin.changepass');
Route::get('/verify-email/{id}','UserController@verifyEmail')->name('user.admin.verifyEmail');
Route::get('/login-as/{user}','UserController@loginAs')->name('user.admin.login-as');

Route::get('/userUpgradeRequest', 'UserController@userUpgradeRequest')->name('user.admin.upgrade');
Route::get('/upgrade/{id}','UserController@userUpgradeRequestApprovedId')->name('user.admin.upgradeId');
Route::post('/userUpgradeRequestApproved', 'UserController@userUpgradeRequestApproved')->name('user.admin.userUpgradeRequestApproved');

Route::post('/applyPlan/{user}','UserController@applyUserPlan')->name('user.admin.applyPlan');
Route::post('/cancelPlan/{userPlan}','UserController@cancelUserPlan')->name('user.admin.cancelPlan');

Route::group(['prefix' => 'role'], function () {
    Route::get('/', 'RoleController@index')->name('user.admin.role.index');
    Route::get('/verifyFields', 'RoleController@verifyFields')->name('user.admin.role.verifyFields');
    Route::get('/permission_matrix', 'RoleController@permission_matrix')->name('user.admin.role.permission_matrix');
    Route::get('/create', 'RoleController@create')->name('user.admin.role.create');
    Route::get('/edit/{id}', 'RoleController@edit')->name('user.admin.role.detail');
    Route::post('/store/{id}', 'RoleController@store')->name('user.admin.role.store');
    Route::post('/verifyFieldsStore', 'RoleController@verifyFieldsStore')->name('user.admin.role.verifyFieldsStore');
    Route::get('/verifyFieldsEdit/{id}', 'RoleController@verifyFieldsEdit')->name('user.admin.role.verifyFieldsEdit');
    Route::post('/bulkEdit', 'RoleController@bulkEdit')->name('user.admin.role.bulkEdit');
    Route::post('/save_permissions', 'RoleController@save_permissions')->name('user.admin.role.save_permissions');
});

Route::group(['prefix' => 'mailing'], function () {
    Route::get('/', 'MailingController@index')->name('user.admin.mailing.index');
    Route::post('/', 'MailingController@store')->name('user.admin.mailing.store');
});

Route::group(['prefix' => 'notice'], function () {
    Route::get('/', 'DashboardNoticeController@index')->name('user.admin.notice.index');
    Route::get('/new', 'DashboardNoticeController@edit')->name('user.admin.notice.new');
    Route::get('{dashboard_notice}/edit', 'DashboardNoticeController@edit')->name('user.admin.notice.edit');
    Route::post('{dashboard_notice?}', 'DashboardNoticeController@store')->name('user.admin.notice.store');
});

Route::group(['prefix' => 'support'], function () {
    Route::get('/', 'SupportController@index')->name('user.admin.support.index');
    Route::get('{ticket}', 'SupportController@show')->name('user.admin.support.show');
    Route::post('{ticket}', 'SupportController@delete')->name('user.admin.support.delete');
    Route::post('/{ticket}/message', 'SupportController@storeMessage')->name('user.admin.support.message.store');
    Route::post('/{ticket}/status', 'SupportController@updateStatus')->name('user.admin.support.status');
});

Route::group(['prefix' => 'verification'], function () {
    Route::get('/', 'VerificationController@index')->name('user.admin.verification.index');
    Route::get('detail/{id}', 'VerificationController@detail')->name('user.admin.verification.detail');
    Route::post('store/{id}', 'VerificationController@store')->name('user.admin.verification.store');
    Route::post('/bulkEdit', 'VerificationController@bulkEdit')->name('user.admin.verification.bulkEdit');
});


Route::group(['prefix'=>'wallet'],function (){
    Route::get('/add-credit/{id}','WalletController@addCredit')->name('user.admin.wallet.addCredit');
    Route::post('/add-credit/{id}','WalletController@store')->name('user.admin.wallet.store');
    Route::get('/report','WalletController@report')->name('user.admin.wallet.report');
    Route::post('/reportBulkEdit','WalletController@reportBulkEdit')->name('user.admin.wallet.reportBulkEdit');

});


Route::group(['prefix' => 'subscriber'], function () {
    Route::get('/', 'SubscriberController@index')->name('user.admin.subscriber.index');
    Route::get('edit/{id}', 'SubscriberController@edit')->name('user.admin.subscriber.edit');
    Route::post('store/{id}', 'SubscriberController@store')->name('user.admin.subscriber.store');
    Route::post('/bulkEdit', 'SubscriberController@bulkEdit')->name('user.admin.subscriber.bulkEdit');
});

Route::get('/export', 'UserController@export')->name('user.admin.export');

Route::group(['prefix'=>'plan'],function (){
    Route::get('/','PlanController@index')->name('user.admin.plan.index');
    Route::get('/edit/{plan}','PlanController@edit')->name('user.admin.plan.edit');
    Route::get('/create','PlanController@edit')->name('user.admin.plan.create');
    Route::post('/store/{id}','PlanController@store')->name('user.admin.plan.store');
    Route::post('/bulkEdit','PlanController@bulkEdit')->name('user.admin.plan.bulkEdit');
    Route::get('/getForSelect2','PlanController@getForSelect2')->name('user.admin.plan.getForSelect2');
});

Route::group(['prefix'=>'promocodes'],function (){
    Route::get('/','PromocodeController@index')->name('user.admin.promocode.index');
    Route::get('/edit/{promocode}','PromocodeController@edit')->name('user.admin.promocode.edit');
    Route::get('/create','PromocodeController@edit')->name('user.admin.promocode.create');
    Route::post('/store/{id}','PromocodeController@store')->name('user.admin.promocode.store');
    Route::post('/bulkEdit','PromocodeController@bulkEdit')->name('user.admin.promocode.bulkEdit');
});

Route::group(['prefix'=>'plan-log'],function (){
    Route::get('/','PlanLogController@index')->name('user.admin.plan_log.index');
    Route::post('/bulkEdit','PlanLogController@bulkEdit')->name('user.admin.plan_log.bulkEdit');
    Route::get('/export','PlanLogController@export')->name('user.admin.plan_log.export');
});

Route::group(['prefix'=>'plan-report'],function (){
    Route::get('/','PlanReportController@index')->name('user.admin.plan_report.index');
    Route::get('/export','PlanReportController@export')->name('user.admin.plan_report.export');
    Route::post('/chart','PlanReportController@chart')->name('user.admin.plan_report.chart');
});

Route::get('/getForSelect2ByRole', 'UserController@getForSelect2ByRole')->name('user.admin.getForSelect2ByRole');
Route::get('/getForSelect2', 'UserController@getForSelect2')->name('user.admin.getForSelect2');
