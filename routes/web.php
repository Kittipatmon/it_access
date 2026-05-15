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
    Route::post('/tracking/{requestNo}/acknowledge', [TrackingController::class, 'acknowledge'])->name('tracking.acknowledge');

    // NDA Routes
    Route::get('/request/{requestNo}/nda', [\App\Http\Controllers\Frontend\ConfidentialityAgreementController::class, 'show'])->name('request.nda');
    Route::post('/request/{requestNo}/nda', [\App\Http\Controllers\Frontend\ConfidentialityAgreementController::class, 'store'])->name('request.nda.store');
    Route::post('/request/{requestNo}/nda/company/agree', [\App\Http\Controllers\Frontend\ConfidentialityAgreementController::class, 'agreeCompany'])->name('request.nda.company.agree');
    Route::post('/request/{requestNo}/nda/witness/{witnessNo}/agree', [\App\Http\Controllers\Frontend\ConfidentialityAgreementController::class, 'agreeWitness'])->name('request.nda.witness.agree');
    Route::get('/request/{requestNo}/nda/export', [\App\Http\Controllers\Frontend\ConfidentialityAgreementController::class, 'export'])->name('request.nda.export');

    // Management Routes (Accessible by all logged in users, e.g. Managers)
    Route::prefix('manage')->name('manage.')->group(function () {
        // Personal Signature Management
        Route::get('/profile/signature', [\App\Http\Controllers\Backend\ProfileController::class, 'signature'])->name('profile.signature');
        Route::post('/profile/signature', [\App\Http\Controllers\Backend\ProfileController::class, 'updateSignature'])->name('profile.signature.update');

        // Approval routes
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
        Route::post('/approvals/{stepId}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{stepId}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    // Backend - Administrative routes (Accessible ONLY by Admin or ICT Dept)
    Route::middleware(['admin'])->prefix('backend')->name('backend.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
        Route::delete('/access-options/category/{category}', [AccessOptionController::class, 'destroyCategory'])->name('access-options.destroy-category');
        Route::post('/access-options/{option}/toggle', [AccessOptionController::class, 'toggleStatus'])->name('access-options.toggle');

        // NDA Configuration
        Route::get('/nda-config', [\App\Http\Controllers\Backend\NdaConfigController::class, 'index'])->name('nda-config.index');
        Route::post('/nda-config', [\App\Http\Controllers\Backend\NdaConfigController::class, 'update'])->name('nda-config.update');

        // Approval Step Configurations
        Route::get('/approval-configs', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'index'])->name('approval-configs.index');
        Route::post('/approval-configs', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'store'])->name('approval-configs.store');
        Route::put('/approval-configs/{id}', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'update'])->name('approval-configs.update');
        Route::delete('/approval-configs/{id}', [\App\Http\Controllers\Backend\ApprovalStepConfigController::class, 'destroy'])->name('approval-configs.destroy');
    });
});
