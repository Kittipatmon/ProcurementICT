<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\CategoryController;
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

    // Procurement Workflows (accessible by staff and admin)
    Route::get('/procurements', [ProcurementController::class, 'index'])->name('procurements.index');
    Route::get('/procurements/create', [ProcurementController::class, 'create'])->name('procurements.create');
    Route::post('/procurements', [ProcurementController::class, 'store'])->name('procurements.store');
    Route::get('/procurements/{id}', [ProcurementController::class, 'show'])->name('procurements.show');
    Route::post('/procurements/{id}/submit', [ProcurementController::class, 'submit'])->name('procurements.submit');
    Route::post('/procurements/{id}/approve', [ProcurementController::class, 'approve'])->name('procurements.approve');
    Route::post('/procurements/{id}/reject', [ProcurementController::class, 'reject'])->name('procurements.reject');
    
    Route::post('/procurements/{id}/pr', [ProcurementController::class, 'createPr'])->name('procurements.pr');
    Route::post('/procurements/{id}/po', [ProcurementController::class, 'createPo'])->name('procurements.po');
    Route::post('/procurements/{id}/deliver', [ProcurementController::class, 'updateDelivery'])->name('procurements.deliver');
    Route::post('/procurements/{id}/complete', [ProcurementController::class, 'complete'])->name('procurements.complete');
    Route::post('/procurements/{id}/comments', [ProcurementController::class, 'addComment'])->name('procurements.comments');
    Route::post('/procurements/{id}/update-step', [ProcurementController::class, 'updateStep'])->name('procurements.update-step');

    // Admin Only Routes
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/tracking', [DashboardController::class, 'tracking'])->name('tracking');
        
        Route::get('/responsibilities', function () {
            $employees = \Illuminate\Support\Facades\DB::connection('mysql_user')
                ->table('employees')
                ->select('id', 'firstname', 'lastname', 'procurement_role')
                ->where('status', 'active')
                ->orderBy('firstname', 'asc')
                ->get();
            return view('responsibilities.index', compact('employees'));
        })->name('responsibilities');
        
        Route::post('/responsibilities/update-role', function (\Illuminate\Http\Request $request) {
            $employeeId = $request->input('employee_id');
            $role = $request->input('role');
            
            if ($employeeId && $role) {
                $currentHolders = \Illuminate\Support\Facades\DB::connection('mysql_user')
                    ->table('employees')
                    ->where('procurement_role', 'LIKE', '%' . $role . '%')
                    ->get();
                
                foreach ($currentHolders as $holder) {
                    $rolesArray = explode(',', $holder->procurement_role);
                    $rolesArray = array_diff($rolesArray, [$role]);
                    $newRoles = empty($rolesArray) ? 'user' : implode(',', $rolesArray);
                    \Illuminate\Support\Facades\DB::connection('mysql_user')
                        ->table('employees')
                        ->where('id', $holder->id)
                        ->update(['procurement_role' => $newRoles]);
                }
                
                $newHolder = \Illuminate\Support\Facades\DB::connection('mysql_user')
                    ->table('employees')
                    ->where('id', $employeeId)
                    ->first();
                
                if ($newHolder) {
                    $rolesArray = $newHolder->procurement_role ? explode(',', $newHolder->procurement_role) : [];
                    if (!in_array($role, $rolesArray)) {
                        $rolesArray = array_diff($rolesArray, ['user']);
                        $rolesArray[] = $role;
                        $newRoles = implode(',', $rolesArray);
                        \Illuminate\Support\Facades\DB::connection('mysql_user')
                            ->table('employees')
                            ->where('id', $employeeId)
                            ->update(['procurement_role' => $newRoles]);
                    }
                }
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false], 400);
        })->name('responsibilities.update');

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

        // Categories Management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});
