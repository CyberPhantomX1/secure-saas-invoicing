<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\InvoiceController as PortalInvoiceController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\CustomerPortalAuth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return redirect()->route('login'); });
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});

/*
|--------------------------------------------------------------------------
| Admin Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadPDF'])->name('invoices.download');
    Route::post('/invoices/{invoice}/toggle-status', [InvoiceController::class, 'toggleStatus'])->name('invoices.toggleStatus');
    Route::post('/invoices/{invoice}/pay', [PaymentController::class, 'createCheckoutSession'])->name('invoices.pay');
});

/*
|--------------------------------------------------------------------------
| Client Portal Routes (Customer Login)
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [PortalAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [PortalAuthController::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware([CustomerPortalAuth::class])->group(function () {
        Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/invoices/{id}', [PortalInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{id}/download', [PortalInvoiceController::class, 'downloadPDF'])->name('invoices.download');
        Route::post('/invoices/{id}/pay', [PortalInvoiceController::class, 'pay'])->name('invoices.pay');
    });
});

/*
|--------------------------------------------------------------------------
| Public Webhook Routes
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/stripe', WebhookController::class)
    ->name('webhooks.stripe')
    ->withoutMiddleware([VerifyCsrfToken::class]);