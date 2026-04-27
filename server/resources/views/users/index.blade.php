<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">User Management</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage team members, roles and hierarchy</p>
            </div>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-5 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- FILTER BAR --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-sm">
                <form method="GET" class="flex flex-wrap gap-3 items-center">

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Role</label>
                        <select name="role"
                            class="w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Designation</label>
                        <select name="designation"
                            class="w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Designations</option>
                            @foreach(['manager' => 'Manager', 'tl' => 'Team Lead', 'agent' => 'Agent'] as $val => $label)
                                <option value="{{ $val }}" {{ request('designation') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Manager</label>
                        <select name="manager_id"
                            class="w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Managers</option>
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}" {{ request('manager_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 items-end pt-5">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                            Filter
                        </button>
                        @if(request()->hasAny(['role','designation','manager_id']))
                            <a href="{{ route('users.index') }}"
                               class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- VIEW TOGGLE --}}
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mr-1">View:</span>
                <a href="{{ route('users.index', array_merge(request()->query(), ['view' => 'table'])) }}"
                   class="px-4 py-1.5 rounded-lg text-sm font-semibold transition
                          {{ $view === 'table' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ⊞ Table
                </a>
                <a href="{{ route('users.index', array_merge(request()->query(), ['view' => 'tree'])) }}"
                   class="px-4 py-1.5 rounded-lg text-sm font-semibold transition
                          {{ $view === 'tree' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ◈ Hierarchy
                </a>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">

                @if($view === 'tree')
                    <div class="p-6 space-y-3">
                        @forelse($users as $user)
                            @include('users.partials.node', ['user' => $user, 'depth' => 0])
                        @empty
                            <div class="text-center py-16 text-gray-400 dark:text-gray-500">
                                <svg class="mx-auto w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                </svg>
                                <p class="text-sm font-medium">No users found</p>
                            </div>
                        @endforelse
                    </div>

                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">User</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Role</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Designation</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Joined</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($users as $user)
                                @php
                                    $roleBadge = match($user->roles->first()?->name) {
                                        'master-admin' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        'admin'        => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'user'         => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        default        => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                    };
                                    $roleLabel = match($user->roles->first()?->name) {
                                        'user'  => 'Agent',
                                        default => ucfirst($user->roles->first()?->name ?? 'No Role'),
                                    };
                                @endphp

                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">

                                    {{-- USER --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-sm flex-shrink-0">
                                                {{ $user->initials }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ROLE --}}
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $roleBadge }}">
                                            {{ $roleLabel }}
                                        </span>
                                    </td>

                                    {{-- DESIGNATION --}}
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600 dark:text-gray-300 capitalize">
                                            {{ $user->designation ?? '—' }}
                                        </span>
                                    </td>

                                    {{-- DATE --}}
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button"
                                                onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->roles->first()->name ?? '' }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-lg transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </button>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                                        <svg class="mx-auto w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium">No users match your filters</p>
                                        <a href="{{ route('users.index') }}" class="text-xs text-indigo-500 hover:underline mt-1 inline-block">Clear filters</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editUserModal"
         class="fixed inset-0 z-50 hidden"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        {{-- BACKDROP --}}
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

        {{-- PANEL --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                {{-- HEADER --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit User</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update name, email or role</p>
                    </div>
                    <button onclick="closeEditModal()"
                        class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-5 space-y-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                                Full Name
                            </label>
                            <input type="text" name="name" id="edit_name"
                                   class="block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                                Email Address
                            </label>
                            <input type="email" name="email" id="edit_email"
                                   class="block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                                Role
                            </label>
                            <select name="role" id="edit_role"
                                    class="block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">
                                        {{ ucfirst($role->name === 'user' ? 'Agent' : $role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, email, role) {
            document.getElementById('editUserForm').action = '/users/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editUserModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close on Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeEditModal();
        });
    </script>
</x-app-layout>
