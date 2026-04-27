<?php
namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class MasterAdminController extends Controller
{
    public function index()
    {
        $data = [
            'total_users' => User::count(),
            'roles_count' => Role::count(),
        ];
        return view('dashboard', $data);
    }
}
