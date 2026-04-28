<?php
// config/menu.php
return [
    [
        'title' => 'Dashboard',
        'route' => 'dashboard',
        'permission' => 'view dashboard'
    ],
    [
        'title' => 'Users',
        'route' => 'users.index',
        'permission' => 'manage user profiles'
    ],
    [
        'title' => 'Reports',
        'route' => 'reports.index',
        'permission' => 'view reports'
    ],
    [
        'title' => 'Settings',
        'route' => 'settings.index',
        'permission' => 'manage system settings'
    ],
    [
        'title' => 'Leads',
        'route' => 'leads.index',
        'permission' => 'view leads'

    ],
    [
        'title' => 'Tickets',
        'route' => 'tickets.index',
        'permission' => 'manage tickets'
    ],
    [
     'title' => 'Contacts',
        'route' => 'contacts.index',
        'permission' => 'view leads'
    ],
    [
        'title' => 'Tracker',
        'route' => 'carriers.index',
        'permission' => 'track carriers'
    ]


];
