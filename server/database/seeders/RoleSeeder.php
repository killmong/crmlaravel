<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -----------------------------------------
        // ALL PERMISSIONS (CENTRAL SOURCE)
        // -----------------------------------------
        $permissions = [

            // Records
            'view own records',
            'edit own records',
            'create records',
            'view all records',
            'delete records',

            // Tasks & Communication
            'manage tasks',
            'assign tasks',
            'use email templates',
            'engage live support',

            // Tickets / Helpdesk
            'view tickets',
            'create tickets',
            'update tickets',
            'resolve tickets',
            'reassign tickets',
            'add internal notes',

            // Users & Roles
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user profiles',
            'assign roles',

            // Reports & Analytics
            'view reports',
            'export reports',
            'view revenue',
            'export financial reports',

            // Compliance & System
            'manage compliance',
            'manage system settings',
            'access audit logs',

            // Data Operations
            'import data',
            'export data',

            'view dashboard',
            //Leads
               // Leads
'view leads',
'create leads',
'edit leads',
'delete leads',
'assign leads',

            ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // -----------------------------------------
        // ROLES
        // -----------------------------------------

        // MASTER ADMIN → full access
        $masterAdmin = Role::firstOrCreate(['name' => 'master-admin']);
        $masterAdmin->syncPermissions(Permission::all());

        // ADMIN → operational + management
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
         'view own records',
            'edit own records',
            'create records',
            'view all records',
            'delete records',

            // Tasks & Communication
            'manage tasks',
            'assign tasks',
            'use email templates',
            'engage live support',

            // Tickets / Helpdesk
            'view tickets',
            'create tickets',
            'update tickets',
            'resolve tickets',
            'reassign tickets',
            'add internal notes',

            // Users & Roles
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user profiles',
            'assign roles',

            // Reports & Analytics
            'view reports',
            'export reports',
            'view revenue',
            'export financial reports',

            // Compliance & System
            'manage compliance',
            'manage system settings',
            'access audit logs',

            // Data Operations
            'import data',
            'export data',


            'view dashboard',

            // Leads
            'view leads',
'create leads',
'edit leads',
'delete leads',
'assign leads',

        ]);

        // AGENT (user)
        $agent = Role::firstOrCreate(['name' => 'user']);
        $agent->syncPermissions([
            'view dashboard',
            'view own records',
            'edit own records',
            'create records',
            'manage tasks',
            'use email templates',
            'engage live support',


            // Tickets
            'view tickets',
            'create tickets',
            'update tickets',
            'resolve tickets',
            'add internal notes',
               'view leads',
'create leads',
'edit leads',
             ]);

        // LIGHT AGENT (read-heavy role)
        $lightAgent = Role::firstOrCreate(['name' => 'light-agent']);
        $lightAgent->syncPermissions([
            'view dashboard',
            'view own records',
            'view tickets',
            'add internal notes',

            'view leads'
        ]);
    }
}
