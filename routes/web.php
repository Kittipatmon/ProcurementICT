<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\LicenseController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

    // Procurement Workflows
    Route::get('/procurements', [ProcurementController::class, 'index'])->name('procurements.index');
    Route::get('/procurements/create', [ProcurementController::class, 'create'])->name('procurements.create');
    Route::post('/procurements', [ProcurementController::class, 'store'])->name('procurements.store');
    Route::get('/procurements/{id}', [ProcurementController::class, 'show'])->name('procurements.show');
    Route::post('/procurements/{id}/submit', [ProcurementController::class, 'submit'])->name('procurements.submit');
    Route::post('/procurements/{id}/approve', [ProcurementController::class, 'approve'])->name('procurements.approve');
    Route::post('/procurements/{id}/reject', [ProcurementController::class, 'reject'])->name('procurements.reject');
    
    // PR/PO Generation (Procurement Role)
    Route::post('/procurements/{id}/pr', [ProcurementController::class, 'createPr'])->name('procurements.pr');
    Route::post('/procurements/{id}/po', [ProcurementController::class, 'createPo'])->name('procurements.po');
    Route::post('/procurements/{id}/deliver', [ProcurementController::class, 'updateDelivery'])->name('procurements.deliver');
    Route::post('/procurements/{id}/complete', [ProcurementController::class, 'complete'])->name('procurements.complete');

    // Comments
    Route::post('/procurements/{id}/comments', [ProcurementController::class, 'addComment'])->name('procurements.comments');

    // Department Budgets
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    // Vendors Directory
    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::put('/vendors/{id}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    // Software Licenses
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::post('/licenses', [LicenseController::class, 'store'])->name('licenses.store');
    Route::put('/licenses/{id}', [LicenseController::class, 'update'])->name('licenses.update');
    Route::delete('/licenses/{id}', [LicenseController::class, 'destroy'])->name('licenses.destroy');
    Route::post('/licenses/{id}/assign', [LicenseController::class, 'assign'])->name('licenses.assign');
    Route::post('/licenses/revoke/{assignment_id}', [LicenseController::class, 'revoke'])->name('licenses.revoke');
});
