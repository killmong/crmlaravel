<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                {{ __('Create New Lead') }}
            </h2>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="text-gray-500">Leads</li>
                    <li class="text-gray-400">/</li>
                    <li class="text-indigo-600 font-medium">Create</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('leads.store') }}" class="space-y-6">
                @csrf

                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                            <p class="text-sm text-gray-500">Essential details to reach out to the prospect.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="name" class="block text-sm font-semibold leading-6 text-gray-900">Lead Name</label>
                                <div class="mt-2">
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('name') ring-red-500 @enderror"
                                        value="{{ old('name') }}" placeholder="John Doe" required>
                                </div>
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-semibold leading-6 text-gray-900">Email Address</label>
                                <div class="mt-2 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </span>
                                    <input type="email" name="email" id="email"
                                        class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        value="{{ old('email') }}" placeholder="john@example.com">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-semibold leading-6 text-gray-900">Phone Number</label>
                                <div class="mt-2 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </span>
                                    <input type="text" name="phone" id="phone"
                                        class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Lead Source & Status</h3>
                            <p class="text-sm text-gray-500">Track where this lead originated from.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="source" class="block text-sm font-semibold leading-6 text-gray-900">Origin Source</label>
                                <select id="source" name="source"
                                    class="mt-2 block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                    <option value="manual">Manual Entry</option>
                                    <option value="website">Website Form</option>
                                    <option value="campaign">Marketing Campaign</option>
                                    <option value="referral">Referral</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-x-4 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                        <a href="{{ route('leads.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">
                            Cancel
                        </a>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
                            Create Lead
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
