<?php

use App\Http\Controllers\Frontend\RequestFormController;
use App\Http\Controllers\Frontend\TrackingController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ApprovalController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\Backend\AccessOptionController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('request.index');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Mock route for admin quick login (optional, but keep if user wants fast testing)
Route::get('/login-admin', function () {
    Auth::login(User::where('email', 'admin@test.com')->first());
    return redirect()->route('backend.dashboard');
})->name('login.admin');

Route::middleware(['auth'])->group(function () {
    // Frontend
    Route::get('/request', [RequestFormController::class, 'index'])->name('request.index');
    Route::post('/request', [RequestFormController::class, 'store'])->name('request.store');

    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::get('/tracking/{requestNo}', [TrackingController::class, 'show'])->name('tracking.show');
    Route::get('/tracking/{requestNo}/print', [TrackingController::class, 'print'])->name('tracking.print');
    Route::delete('/tracking/{requestNo}', [TrackingController::class, 'destroy'])->name('tracking.destroy');

    // Backend (Normally would have 'role:admin' middleware)
    Route::prefix('backend')->name('backend.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Personal Signature Management
        Route::get('/profile/signature', [\App\Http\Controllers\Backend\ProfileController::class, 'signature'])->name('profile.signature');
        Route::post('/profile/signature', [\App\Http\Controllers\Backend\ProfileController::class, 'updateSignature'])->name('profile.signature.update');

        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
        Route::post('/approvals/{stepId}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{stepId}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/{id}/complete', [ApprovalController::class, 'complete'])->name('approvals.complete');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/toggle', [UserManagementController::class, 'toggleStatus'])->name('users.toggle');
        Route::get('/users/{id}/signature', [\App\Http\Controllers\Backend\ProfileController::class, 'adminSignature'])->name('users.signature');
        Route::post('/users/{id}/signature', [\App\Http\Controllers\Backend\ProfileController::class, 'adminUpdateSignature'])->name('users.signature.update');

        // Access Options CRUD
        Route::get('/access-options', [AccessOptionController::class, 'index'])->name('access-options.index');
        Route::post('/access-options', [AccessOptionController::class, 'store'])->name('access-options.store');
        Route::put('/access-options/{option}', [AccessOptionController::class, 'update'])->name('access-options.update');
        Route::delete('/access-options/{option}', [AccessOptionController::class, 'destroy'])->name('access-options.destroy');
        Route::post('/access-options/{option}/toggle', [AccessOptionController::class, 'toggleStatus'])->name('access-options.toggle');

        // Approval Step Configurations
        Route::get('/approval-configs', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'index'])->name('approval-configs.index');
        Route::post('/approval-configs', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'store'])->name('approval-configs.store');
        Route::put('/approval-configs/{id}', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'update'])->name('approval-configs.update');
        Route::delete('/approval-configs/{id}', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'destroy'])->name('approval-configs.destroy');
    });
});
