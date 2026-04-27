<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            System Settings
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- General Settings -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">General Settings</h3>

                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-gray-600">App Name</label>
                        <input type="text" value="CRM PRO"
                               class="w-full mt-1 border-gray-300 rounded">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Timezone</label>
                        <select class="w-full mt-1 border-gray-300 rounded">
                            <option>Asia/Kolkata</option>
                            <option>UTC</option>
                        </select>
                    </div>

                </form>
            </div>

            <!-- Security Settings -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Security</h3>

                <div class="space-y-3">

                    <div class="flex justify-between items-center">
                        <span>Password Policy</span>
                        <button class="text-indigo-600">Edit</button>
                    </div>

                    <div class="flex justify-between items-center">
                        <span>Two-Factor Authentication</span>
                        <button class="text-indigo-600">Enable</button>
                    </div>

                </div>
            </div>

            <!-- System Controls (Master Admin Only) -->
            @role('master-admin')
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-4">
                    System Controls
                </h3>

                <div class="space-y-3">
                    <button class="bg-red-600 text-white px-4 py-2 rounded">
                        Clear Cache
                    </button>

                    <button class="bg-red-500 text-white px-4 py-2 rounded">
                        Reset System
                    </button>
                </div>
            </div>
            @endrole

        </div>
    </div>

</x-app-layout>
