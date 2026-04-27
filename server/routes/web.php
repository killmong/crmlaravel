<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LeadController;
/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (CRM Core)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])
        ->middleware('permission:view dashboard')
        ->name('dashboard');

    Route::get('/settings', [SettingsController::class, 'index'])
        ->middleware('permission:manage system settings')
        ->name('settings.index');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:view reports')
        ->name('reports.index');

    // ⚠️ MUST be above Route::resource()
    Route::get('/users/roles', [UserManagementController::class, 'getRoles'])
        ->name('users.roles');

    // User Management
    Route::resource('users', UserManagementController::class)
        ->middleware('permission:manage user profiles');
     Route::resource('leads', LeadController::class)
        ->middleware('permission:view leads');

    Route::post('/leads/{id}/convert', [LeadController::class, 'convert'])
        ->middleware('permission:view leads')
        ->name('leads.convert');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
