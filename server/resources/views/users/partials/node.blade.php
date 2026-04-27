@php
    $depth    = $depth ?? 0;
    $hasKids  = $user->subordinatesRecursive->isNotEmpty();
    $nodeId   = 'node-' . $user->id;

    $depthColor = match(true) {
        $depth === 0 => 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20',
        $depth === 1 => 'border-violet-400 bg-violet-50 dark:bg-violet-900/20',
        $depth === 2 => 'border-sky-400 bg-sky-50 dark:bg-sky-900/20',
        default      => 'border-gray-300 bg-gray-50 dark:bg-gray-800',
    };

    $badgeColor = match($depth) {
        0 => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300',
        1 => 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300',
        2 => 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300',
        default => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<div class="relative {{ $depth > 0 ? 'ml-8 mt-2' : 'mt-2' }}">

    {{-- Vertical connector line --}}
    @if($depth > 0)
        <div class="absolute -left-4 top-0 h-full w-px bg-gray-200 dark:bg-gray-700"></div>
        <div class="absolute -left-4 top-5 w-4 h-px bg-gray-200 dark:bg-gray-700"></div>
    @endif

    {{-- NODE CARD --}}
    <div class="border-l-4 {{ $depthColor }} rounded-xl p-3.5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow group">

        <div class="flex items-center gap-3">
            {{-- Avatar --}}
            <div class="h-9 w-9 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0 {{ $badgeColor }}">
                {{ $user->initials }}
            </div>

            {{-- Info --}}
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $user->name }}</span>
                    @if($user->roles->first())
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-md {{ $badgeColor }}">
                            {{ ucfirst($user->roles->first()->name === 'user' ? 'Agent' : $user->roles->first()->name) }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $user->designation ?? 'No designation' }}</span>
                    @if($user->department)
                        <span class="text-gray-300 dark:text-gray-600">·</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->department }}</span>
                    @endif
                    @if($hasKids)
                        <span class="text-gray-300 dark:text-gray-600">·</span>
                        <span class="text-xs text-indigo-500 dark:text-indigo-400 font-medium">
                            {{ $user->subordinatesRecursive->count() }} {{ Str::plural('report', $user->subordinatesRecursive->count()) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button type="button"
                onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->roles->first()->name ?? '' }}')"
                class="p-1.5 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition"
                title="Edit user">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>

            @if($hasKids)
                <button type="button"
                    onclick="toggleChildren('{{ $nodeId }}')"
                    class="p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    title="Toggle team">
                    <svg id="{{ $nodeId }}-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- CHILDREN --}}
    @if($hasKids)
        <div id="{{ $nodeId }}" class="block">
            @foreach($user->subordinatesRecursive as $child)
                @include('users.partials.node', ['user' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>

<script>
    function toggleChildren(nodeId) {
        const el   = document.getElementById(nodeId);
        const icon = document.getElementById(nodeId + '-icon');
        const hidden = el.classList.toggle('hidden');
        icon.style.transform = hidden ? 'rotate(-90deg)' : 'rotate(0deg)';
    }
</script>
