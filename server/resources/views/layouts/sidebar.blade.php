<aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:flex flex-col">
    <div class="h-16 flex items-center justify-center border-b border-gray-800">
        <span class="text-2xl font-bold tracking-wider text-indigo-400">CRM PRO</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
@foreach($menu as $item)
    @can($item['permission'])
        <a href="{{ route($item['route']) }}"
           class="flex items-center px-4 py-3 rounded-lg transition-colors
           {{ request()->routeIs($item['route']) ? 'bg-gray-800' : 'hover:bg-gray-700' }}">

            <!-- icon -->
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
         
            {{ $item['title'] }}
        </a>
    @endcan
@endforeach

    </nav>

    <div class="p-4 bg-gray-950 border-t border-gray-800">
        <a href="{{ route('profile.edit') }}" class="flex items-center w-full group">
            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 group-hover:text-indigo-300 transition-colors">Edit Profile &rarr;</p>
            </div>
        </a>
    </div>
</aside>
