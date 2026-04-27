<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{

public function index()
{
    return view('dashboard', [
        'total_users' => User::count(),
        'roles_count' => Role::count(),
        'permissions_count' => Permission::count(),
        'user' => auth()->user(),
    ]);
}
}
