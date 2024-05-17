<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>config('job.job_route_prefix')],function(){
    Route::get('/','JobController@index')->name('job.search');
    Route::post('/search-count','JobController@JobSearchCount')->name('job.search.count');
    Route::get('/{slug}','JobController@detail')->name('job.detail');

    Route::post('/apply-job', 'JobController@applyJob')->name('job.apply-job');
    Route::get('/'.config('job.job_category_route_prefix').'/{slug}', 'JobController@categoryIndex')->name('job.category.index');
    Route::get('/'.config('job.job_location_route_prefix').'/{slug}', 'JobController@locationIndex')->name('job.location.index');
    Route::get('/{cat_slug}/{location_slug}', 'JobController@categoryLocationIndex')->name('job.category.location.index');
});

Route::group(['prefix'=> 'user/jobs', 'middleware'=>'auth'],function() {
    Route::get('/', 'UserJobController@manageJobs')->name('user.all.jobs');
    Route::get('/new', 'UserJobController@createJob')->name('user.create.job');
    Route::get('/edit/{id}', 'UserJobController@editJob')->name('user.edit.job');
    Route::post('/store/{id}', 'UserJobController@storeJob')->name('user.store.job');
    Route::get('/choose-plan/{job}', 'UserJobController@choosePlan')->name('user.choose.job.plan');
    Route::post('/choose-plan/{job}', 'UserJobController@storePlan')->name('user.store.job.plan');
    Route::post('/update/{job}', 'UserJobController@update')->name('user.update.job');
    Route::post('/delete/{id}', 'UserJobController@deleteJob')->name('user.delete.job');
    Route::get('/export','UserJobController@jobExport')->name('user.job.export');
    Route::get('/applied-export','UserJobController@appliedJobExport')->name('user.appliedJobs.export');
    Route::post('/bulk','UserJobController@bulk')->name('user.bulk.job');
});

Route::group(['prefix'=> 'user/applicants', 'middleware'=>'auth'],function(){
    Route::get('/', 'UserJobController@applicants')->name('user.applicants');
    Route::get('/create','UserJobController@applicantsCreate')->name('user.applicants.create');
    Route::post('/store','UserJobController@applicantsStore')->name('user.applicants.store');
    Route::post('/update/{id}','UserJobController@applicantsChangeStatus')->name('user.applicants.update');
    Route::post('/delete/{jobCandidate}','UserJobController@applicantsDelete')->name('user.applicants.delete');
    Route::get('/export','UserJobController@applicantsExport')->name('user.applicants.export');
    Route::post('/bulk','UserJobController@bulk')->name('user.applicants.bulk');
});
