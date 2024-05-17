<?php

use App\Http\Controllers\Admin\ResourcesPostController;
use App\Http\Controllers\Auth\ModalRegisteredUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleSocialiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\UserProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalDocumentController;
use App\Http\Controllers\WhitelistedKeywordsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WhitelistedAccountsController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserProjectController as AdminUserProjectController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('auth/google', [GoogleSocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleSocialiteController::class, 'handleCallback']);
Route::post('/modal-register', [ModalRegisteredUserController::class, 'register'])->name('modal.register');

Route::get('terms-conditions', function (){
    return view('pages.terms-conditions');
})->name('pages.terms.conditions');
Route::get('faq', function (){
    return view('pages.faq');
})->name('pages.faq');
Route::get('resources', [ResourcesController::class, 'index'])->name('pages.resources');
Route::get('resources/{resource}', [ResourcesController::class, 'show'])->name('pages.resources.single');
Route::get('about', function (){
    return view('pages.about');
})->name('pages.about');
Route::get('pricing', [PlanController::class, 'list'])->name('pages.pricing');

Route::get('contact', [ContactController::class, 'show'])->name('pages.contact');
Route::post('mailContactForm', [ContactController::class, 'mailContactForm'])->name('contact.send');

Route::group(['prefix' => 'account', 'middleware' => ['auth']], function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('account.notifications');
    Route::get('/mark-as-read', [NotificationController::class, 'markNotification'])->name('account.markNotification');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/exportGoogleSearch/{projectReport}', [UserProjectController::class, 'exportGoogleSearchReport'])->name('project.exportGoogleSearch');
    Route::get('/exportGoogleImage/{projectReport}', [UserProjectController::class, 'exportGoogleImagesReport'])->name('project.exportGoogleImage');
    Route::get('/exportSocialMedia/{projectReport}', [UserProjectController::class, 'exportSocialMediaReport'])->name('project.exportSocialMedia');
    Route::get('/exportAtResource/{projectReport}', [UserProjectController::class, 'exportAtResourceReport'])->name('project.exportAtResource');

    Route::get('/download-file/{file}', [LegalDocumentController::class, 'download'])->name('legal.document.download');

    Route::group(['middleware' => ['projectAccess']], function () {
        Route::get('/user-project-{project}/report-{report}', [UserProjectController::class, 'userReport'])->name('project.report');
    });
});

Route::group(['prefix' => 'user', 'middleware' => ['auth', 'role:customer']], function () {

    Route::get('/create-project', [DashboardController::class, 'createProject'])->name('createProject');

    Route::group(['middleware' => ['project']], function () {

        Route::group(['middleware' => ['projectAccess']], function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
        });
        Route::get('/select-project/{project}', [DashboardController::class, 'selectProject'])->name('selectProject');

        Route::get('plans', [PlanController::class, 'index'])->name('user.plans');
        Route::get('plans/{plan}', [PlanController::class, 'show'])->name("plans.show");
        Route::post('subscription', [PlanController::class, 'subscription'])->name("subscription.create");
        Route::post('cancel', [PlanController::class, 'cancelPlan'])->name("subscription.cancel");


        Route::group(['prefix' => 'accounts', 'middleware' => ['auth']], function () {
            Route::get('/', [WhitelistedAccountsController::class, 'index'])->name('user.accounts');
            Route::post('/create', [WhitelistedAccountsController::class, 'create'])->name('user.accounts.create');
            Route::post('/import', [WhitelistedAccountsController::class, 'import'])->name('user.accounts.import');
            Route::get('/delete/{whitelistedAccount}', [WhitelistedAccountsController::class, 'destroy'])->name('user.accounts.delete');
        });

        Route::group(['prefix' => 'keywords', 'middleware' => ['auth']], function () {
            Route::get('/', [WhitelistedKeywordsController::class, 'index'])->name('user.keywords');
            Route::post('/create', [WhitelistedKeywordsController::class, 'create'])->name('user.keywords.create');
            Route::post('/import', [WhitelistedKeywordsController::class, 'import'])->name('user.keywords.import');
            Route::get('/delete/{whitelistedKeyword}', [WhitelistedKeywordsController::class, 'destroy'])->name('user.keywords.delete');
        });

        Route::group(['prefix' => 'legal-document', 'middleware' => ['auth']], function () {
            Route::get('/', [LegalDocumentController::class, 'index'])->name('user.document');
            Route::post('/upload', [LegalDocumentController::class, 'store'])->name('user.document.store');
            Route::get('/delete/{legalDocument}', [LegalDocumentController::class, 'destroy'])->name('user.document.delete');
            Route::delete('files/{fileId}', [LegalDocumentController::class, 'destroy'])->name('user.document.destroy');
        });
    });
});

Route::group(['prefix' => User::ROLE_AGENT, 'middleware' => ['auth', 'role:agent|super_admin']], function () {

    Route::get('/dashboard', [UserProjectController::class, 'agentDashboard'])->name('agent.dashboard');
    Route::get('/user-project/{project}', [UserProjectController::class, 'userProject'])->name('agent.user.project');
    Route::post('/report/{project}', [UserProjectController::class, 'report'])->name('agent.projectReport');

    Route::get('/exportAccounts/{project}', [UserProjectController::class, 'exportWhitelistedAccounts'])->name('agent.project.exportAccounts');
    Route::get('/exportKeywords/{project}', [UserProjectController::class, 'exportWhitelistedKeywords'])->name('agent.project.exportKeywords');

    Route::get('/projects', [AdminUserProjectController::class, 'index'])->name('agent.projects');
    Route::get('/customer/{customer}', [AdminUserController::class, 'customer'])->name('agent.customer.view');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:super_admin']], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::get('/users/edit/{user}', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::post('/users/update/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/destroy/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/resources', [ResourcesPostController::class, 'index'])->name('admin.resources.index');
    Route::get('/resources/create', [ResourcesPostController::class, 'create'])->name('admin.resources.create');
    Route::get('/resources/edit/{resource}', [ResourcesPostController::class, 'edit'])->name('admin.resources.edit');
    Route::post('/resources/store', [ResourcesPostController::class, 'store'])->name('admin.resources.store');
    Route::post('/resources/update/{resource}', [ResourcesPostController::class, 'update'])->name('admin.resources.update');
    Route::post('/resources/destroy/{resource}', [ResourcesPostController::class, 'destroy'])->name('admin.resources.destroy');
    Route::post('/resources/imageUpload/', [ResourcesPostController::class, 'imageUpload'])->name('admin.resources.imageUpload');

    Route::post('/projects/{project}/assignAgent', [AdminUserProjectController::class, 'assignAgent'])->name('admin.projects.assignAgent');
});

require __DIR__ . '/auth.php';
