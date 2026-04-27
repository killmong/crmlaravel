<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 text-lg font-medium">
                    Welcome back, Master Admin! Here is your system overview.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Users</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $total_users }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Active Roles</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $roles_count }}</div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
