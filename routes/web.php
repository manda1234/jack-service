<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pelanggan\AuthController as PelangganAuthController;
use App\Http\Controllers\Pelanggan\ServiceRequestController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\SparepartApprovalController;
use App\Http\Controllers\Pelanggan\PaymentController as PelangganPaymentController; // <-- DITAMBAHKAN, dulu tidak di-import

use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomerController;

use App\Http\Controllers\Teknisi\ServiceController as TeknisiServiceController;
use App\Http\Controllers\Teknisi\PaymentController as TeknisiPaymentController;
use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboardController;
use App\Http\Controllers\Teknisi\ScheduleController;

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Pelanggan
|--------------------------------------------------------------------------
*/

Route::prefix('pelanggan')->name('pelanggan.')->group(function () {

    Route::middleware('guest:customer')->group(function () {
        Route::get('/register', [PelangganAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [PelangganAuthController::class, 'register'])->name('register.store');
        Route::get('/login', [PelangganAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [PelangganAuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [PelangganAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');

        Route::resource('services', ServiceRequestController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::post('/service-spareparts/{serviceSparepart}/approve', [SparepartApprovalController::class, 'approve'])->name('spareparts.approve');
        Route::post('/service-spareparts/{serviceSparepart}/reject', [SparepartApprovalController::class, 'reject'])->name('spareparts.reject');

        // --- Pembayaran Pelanggan ---
        // Dulu route ini salah taruh di grup Admin dan controllernya belum di-import,
        // sehingga route 'pelanggan.services.payment' yang dipanggil di Blade tidak pernah ada.
        Route::get('/services/{service}/payment', [PelangganPaymentController::class, 'show'])->name('services.payment');
        Route::post('/services/{service}/payment/qris', [PelangganPaymentController::class, 'storeQris'])->name('services.payment.qris');
        Route::post('/services/{service}/payment/tunai', [PelangganPaymentController::class, 'storeTunai'])->name('services.payment.tunai');
    });
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [AdminServiceController::class, 'show'])->name('services.show');
    Route::post('/services/{service}/reject', [AdminServiceController::class, 'reject'])->name('services.reject');
    Route::post('/services/{service}/assign', [AdminServiceController::class, 'assign'])->name('services.assign');
    Route::post('/services/{service}/receive', [AdminServiceController::class, 'receiveItem'])->name('services.receive');
    Route::post('/services/{service}/payment', [AdminPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

    Route::resource('technicians', TechnicianController::class)->except(['show']);
    Route::resource('spareparts', SparepartController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::post('/services/{service}/set-final-cost', [AdminServiceController::class, 'setFinalCost'])->name('services.setFinalCost');
    Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');

    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

/*
|--------------------------------------------------------------------------
| Teknisi
|--------------------------------------------------------------------------
*/

Route::prefix('teknisi')->name('teknisi.')->middleware(['auth', 'role:Teknisi'])->group(function () {
    Route::get('/dashboard', [TeknisiDashboardController::class, 'index'])->name('dashboard');

    Route::get('/services', [TeknisiServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [TeknisiServiceController::class, 'show'])->name('services.show');

    Route::post('/services/{service}/arrive', [TeknisiServiceController::class, 'arriveAtLocation'])->name('services.arrive');
    Route::post('/services/{service}/start-check', [TeknisiServiceController::class, 'startCheck'])->name('services.startCheck');
    Route::post('/services/{service}/diagnosis', [TeknisiServiceController::class, 'saveDiagnosis'])->name('services.diagnosis');
    Route::post('/services/{service}/sparepart', [TeknisiServiceController::class, 'addSparepart'])->name('services.sparepart');
    Route::post('/services/{service}/complete', [TeknisiServiceController::class, 'complete'])->name('services.complete');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/services/{service}/confirm-schedule', [TeknisiServiceController::class, 'confirmSchedule'])->name('services.confirmSchedule');
});

/*
|--------------------------------------------------------------------------
| Invoice
|--------------------------------------------------------------------------
*/

Route::get('/invoice/{service}', [InvoiceController::class, 'download'])
    ->name('invoice.download')
    ->middleware(['auth.any']);

require __DIR__.'/auth.php';