<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

   use App\Http\Requests\StoreUserRequest;
class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
 public function index(Request $request)
{
    $view = $request->get('view', 'table');

    $query = User::with('roles');

    // Only load recursive tree when needed
    if ($view === 'tree') {
        $query->with('subordinatesRecursive')->topLevel();
    }

    // Apply scopes cleanly
    if ($request->filled('role')) {
        $query->ofRole($request->role);
    }

    if ($request->filled('designation')) {
        $query->ofDesignation($request->designation);
    }

    if ($request->filled('manager_id')) {
        $query->underManager($request->manager_id);
    }

    $users    = $query->get();
    $roles    = Role::all();
    $managers = User::topLevel()->get(); // Uses scope

    return view('users.index', compact('users', 'roles', 'view', 'managers'));
}

    /**
     * Get all roles with permissions — for AJAX dropdown
     */
    public function getRoles()
    {
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id'          => $role->name,
                'label'       => ucfirst(str_replace('-', ' ', $role->name)),
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ];
        });

        return response()->json($roles);
    }

    /**
     * Show form to create a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */

public function store(StoreUserRequest $request)
{
    if ($request->role === 'master-admin' && !$request->user()->hasRole('master-admin')) {
        return response()->json(['message' => 'Unauthorized action'], 403);
    }

    $user = User::create([
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => $request->password, // auto-hashed via $casts
        'manager_id' => $request->manager_id,
    ]);

    $user->assignRole($request->role);

    return response()->json([
        'message'              => 'User created successfully!',
        'user'                 => $user->only(['id', 'name', 'email']),
        'assigned_permissions' => $user->getAllPermissions()->pluck('name'),
    ]);
}
    /**
     * Display a specific user
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show form to edit user
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
            'role'  => ['required', 'exists:roles,name'],
        ]);

        if ($request->role === 'master-admin' && !$request->user()->hasRole('master-admin')) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'User updated successfully!',
            'user'    => $user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('master-admin')) {
            return response()->json(['message' => 'Cannot delete master admin'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }
}
