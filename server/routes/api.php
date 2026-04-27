<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserManagementController;

// Protect API routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Check if the user is at least an admin or master-admin
    

});
