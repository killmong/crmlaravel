<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Lead Details</h2>
    </x-slot>

    <div class="p-6 max-w-3xl space-y-4">

        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-bold mb-2">{{ $lead->name }}</h3>

            <p><strong>Email:</strong> {{ $lead->email ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $lead->phone ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($lead->status) }}</p>
            <p><strong>Source:</strong> {{ $lead->source }}</p>

            <p><strong>Owner:</strong>
                {{ $lead->user->name ?? 'Unassigned' }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">

            @can('edit leads')
            <a href="{{ route('leads.edit', $lead->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">
                Edit
            </a>
            @endcan

            @can('delete leads')
            <form action="{{ route('leads.destroy', $lead->id) }}" method="POST"
                  onsubmit="return confirm('Delete this lead?')">
                @csrf
                @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded">
                    Delete
                </button>
            </form>
            @endcan

        </div>
    </div>
</x-app-layout>
