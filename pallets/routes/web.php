<?php

use App\Http\Controllers\LeadWizardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');


Route::group(['prefix' => 'wizard'], function() {
    Route::get('/', [LeadWizardController::class, 'index'])->name('wizard');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified']], function() {
    Route::view('/dashboard', 'admin/dashboard')->name('dashboard');
    Route::view('/profile', 'admin/profile')->name('profile');

    Route::view('/leads', 'admin/leads')->name('leads.list');
    Route::view('/lead/{lead}', 'admin/lead')->name('lead.show');

    Route::view('/pallet-modules', 'admin/pallet-module-list')->name('pallet.modules.list');
    Route::view('/pallet-module/{module}', 'admin/pallet-module')->name('pallet.module.show');
});

require __DIR__.'/auth.php';
