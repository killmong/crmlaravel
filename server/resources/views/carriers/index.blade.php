<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-indigo-500 rounded-full"></div>
                <h2 class="font-bold text-xl text-slate-800 tracking-tight">Carriers</h2>
            </div>

            {{-- Optional: Add a 'New Carrier' button here later --}}
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-100 shadow-sm rounded-xl overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-xs">Company</th>
                                <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-xs">MC Number</th>
                                <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-xs">DOT</th>
                                <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-xs">State</th>
                                <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-xs">Certified</th> <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-xs">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($carriers as $carrier)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $carrier->company_name }}</td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $carrier->mc_number }}</td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $carrier->dot_number }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $carrier->state }}</td>

                                {{-- isCertified node mapped in UI -- Requirement 5 --}}
                                <td class="px-6 py-4">
                                    @if($carrier->is_certified)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            Certified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                            Not Certified
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold uppercase tracking-wider {{ $carrier->status === 'active' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $carrier->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($carriers->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                        {{ $carriers->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
