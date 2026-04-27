<x-app-layout>

    <!-- Header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reports & Analytics
            </h2>

            @can('export reports')
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                    Export Report
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @can('view reports')
                <div class="bg-white shadow rounded-lg p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500">Total Reports</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $totalReports ?? 0 }}
                    </p>
                </div>
                @endcan

                @can('view revenue')
                <div class="bg-white shadow rounded-lg p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Revenue</p>
                    <p class="text-3xl font-bold text-gray-800">
                        ₹{{ $totalRevenue ?? 0 }}
                    </p>
                </div>
                @endcan

                @can('view reports')
                <div class="bg-white shadow rounded-lg p-6 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Active Reports</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $activeReports ?? 0 }}
                    </p>
                </div>
                @endcan

            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>
                        <label class="text-sm text-gray-600">From</label>
                        <input type="date" name="from"
                               class="w-full border-gray-300 rounded mt-1">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">To</label>
                        <input type="date" name="to"
                               class="w-full border-gray-300 rounded mt-1">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Type</label>
                        <select name="type" class="w-full border-gray-300 rounded mt-1">
                            <option value="">All</option>
                            <option value="sales">Sales</option>
                            <option value="users">Users</option>
                            <option value="tickets">Tickets</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-900">
                            Filter
                        </button>
                    </div>

                </form>
            </div>

            <!-- Reports Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-left">

                    <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Report Name</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($reports ?? [] as $report)
                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 font-medium">
                                    {{ $report->name }}
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ ucfirst($report->type) }}
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $report->created_at->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">

                                    @can('view reports')
                                        <a href="#"
                                           class="text-indigo-600 hover:underline">
                                            View
                                        </a>
                                    @endcan

                                    @can('export reports')
                                        <a href="#"
                                           class="text-green-600 hover:underline">
                                            Export
                                        </a>
                                    @endcan

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500">
                                    No reports found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-app-layout>
