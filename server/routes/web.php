<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarrierController;
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
    Route::resource('leads', LeadController::class);

    // Convert lead → contact  (POST /leads/{id}/convert)
    Route::post('leads/{id}/convert', [LeadController::class, 'convert'])
         ->name('leads.convert');

    /*
    |--------------------------------------------------------------------------
    | Contact Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/carriers', [CarrierController::class, 'index'])->name('carriers.index');
Route::get('/carriers/{carrier}', [CarrierController::class, 'show'])->name('carriers.show');
    Route::resource('contacts', ContactController::class);

    // Restore soft-deleted contact  (POST /contacts/{id}/restore)
    Route::post('contacts/{id}/restore', [ContactController::class, 'restore'])
         ->name('contacts.restore');
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
