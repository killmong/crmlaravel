
<x-app-layout>
<div class="container mx-auto px-4 py-8">

    {{-- ═════════════════ HEADER & ROLE-BASED TITLES ═════════════════ --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                @hasanyrole('master-admin|admin')
                    Global Contacts Dashboard
                @else
                    @if($users->isNotEmpty())
                        Team Contacts Dashboard
                    @else
                        My Contacts
                    @endif
                @endhasanyrole
            </h1>
            <p class="text-sm text-gray-500 mt-1">
            </p>
        </div>
        <div>
            <a href="{{ route('contacts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                + New Contact
            </a>
        </div>
    </div>

    {{-- ═════════════════ CONTACTS TABLE ═════════════════ --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 font-medium text-gray-900">Name & Company</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Contact Info</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Type / Status</th>

                    {{-- Only show the Assignee column if they are an admin or manager --}}
                    @if($users->isNotEmpty())
                        <th class="px-6 py-4 font-medium text-gray-900">Assigned To</th>
                    @endif

                    <th class="px-6 py-4 font-medium text-gray-900 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($contacts as $contact)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $contact->name }}</div>
                            <div class="text-xs text-gray-500">{{ $contact->company ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $contact->email ?? 'No Email' }}</div>
                            <div class="text-xs text-gray-500">{{ $contact->phone ?? 'No Phone' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs uppercase font-semibold">
                                {{ $contact->type }}
                            </span>
                            <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded text-xs uppercase font-semibold">
                                {{ $contact->status }}
                            </span>
                        </td>

                        {{-- Assignee Data (Admins & Managers Only) --}}
                        @if($users->isNotEmpty())
                            <td class="px-6 py-4">
                                @if($contact->assignee)
                                    <span class="text-gray-800">{{ $contact->assignee->name }}</span>
                                @else
                                    <span class="text-red-500 text-xs font-semibold">Unassigned</span>
                                @endif
                            </td>
                        @endif

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('contacts.show', $contact->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('contacts.edit', $contact->id) }}" class="text-gray-600 hover:text-gray-900 mr-3">Edit</a>

                            {{-- ASSIGN BUTTON: Only visible if the user has people to assign to --}}
                            @if($users->isNotEmpty())
                                <button onclick="openAssignModal({{ $contact->id }})" class="text-amber-600 hover:text-amber-900 font-semibold">
                                    Assign
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No contacts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="mt-4">
        {{ $contacts->links() }}
    </div>

    {{-- ═════════════════ ASSIGN MODAL ═════════════════ --}}
    @if($users->isNotEmpty())
        <div id="assignModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50 rounded-t-lg">
                    <h3 class="text-lg font-bold text-gray-800">Assign Contact</h3>
                    <button onclick="closeModal('assignModal')" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                {{-- Form action is set dynamically via JavaScript below --}}
                <form id="assignForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Agent</label>
                        <select name="assigned_to" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border" required>
                            <option value="" disabled selected>Select an agent...</option>
                            @foreach($users as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end rounded-b-lg">
                        <button type="button" onclick="closeModal('assignModal')" class="bg-white border text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded hover:bg-amber-600 font-semibold">Confirm Assign</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Javascript to handle dynamic modal form routing --}}
        <script>
            function openAssignModal(contactId) {
                // Update the form action URL to target the correct contact
                const form = document.getElementById('assignForm');
                form.action = `/contacts/${contactId}`;

                // Show the modal
                document.getElementById('assignModal').classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
        </script>
    @endif

</div>
</x-app-layout>
