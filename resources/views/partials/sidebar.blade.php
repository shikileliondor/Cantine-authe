@php
    $menuItems = [
        [
            'label' => 'Accueil',
            'route' => 'dashboard',
            'href' => Route::has('dashboard') ? route('dashboard') : '#',
            'icon' => 'home',
        ],
        [
            'label' => 'Gestion',
            'icon' => 'gestion',
            'children' => [
                [
                    'label' => 'Élèves & classes',
                    'route' => 'gestion.index',
                    'href' => Route::has('gestion.index') ? route('gestion.index') : '#',
                ],
            ],
        ],
        [
            'label' => 'Comptabilité',
            'route' => 'comptabilite.index',
            'href' => Route::has('comptabilite.index') ? route('comptabilite.index') : '#',
            'icon' => 'wallet',
        ],
        [
            'label' => 'Paramètres',
            'route' => 'settings.index',
            'href' => Route::has('settings.index') ? route('settings.index') : '#',
            'icon' => 'settings',
        ],
    ];

    $icons = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 11.204 3.045a1.125 1.125 0 0 1 1.592 0L21.75 12M4.5 9.75V19.5a.75.75 0 0 0 .75.75h4.5v-5.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v5.25h4.5a.75.75 0 0 0 .75-.75V9.75" />',
        'gestion' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v4.5H3.75V4.5Zm0 10.5h7.5v4.5h-7.5V15Zm10.5-4.5h6v9h-6v-9Z" />',
        'wallet' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12V9a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9v6a2.25 2.25 0 0 0 2.25 2.25h13.5A2.25 2.25 0 0 0 21 15v-3Zm0 0h-3.75a1.5 1.5 0 0 0 0 3H21m-3.75-3a1.5 1.5 0 0 1 0-3H21" />',
        'settings' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 1 15 0m-15 0a7.5 7.5 0 0 0 15 0m-15 0H3m1.5 0h15m-1.5 0H21" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v7.5M8.25 12h7.5" />',
    ];
@endphp

<aside id="app-sidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200 bg-slate-900 text-slate-100 transition-transform duration-300 ease-out lg:translate-x-0">
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-800 px-6 py-5">
            <p class="text-lg font-bold tracking-wide text-white">Cantine Primaire</p>
            <p class="text-sm text-slate-400">Gestion scolaire</p>
        </div>

        <nav class="flex-1 space-y-2 px-3 py-4">
            @foreach ($menuItems as $index => $item)
                @php
                    $hasChildren = isset($item['children']);
                    $isActive = $hasChildren
                        ? collect($item['children'])->contains(fn ($child) => request()->routeIs($child['route']))
                        : request()->routeIs($item['route']);
                @endphp

                @if ($hasChildren)
                    @php($submenuId = 'sidebar-submenu-' . $index)
                    <button
                        type="button"
                        data-submenu-toggle="{{ $submenuId }}"
                        aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                        class="group flex w-full items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                {!! $icons[$item['icon']] !!}
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div id="{{ $submenuId }}" class="mt-1 space-y-1 pl-11 {{ $isActive ? '' : 'hidden' }}">
                        @foreach ($item['children'] as $child)
                            <a
                                href="{{ $child['href'] }}"
                                class="block rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs($child['route']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                            >
                                {{ $child['label'] }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <a
                        href="{{ $item['href'] }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            {!! $icons[$item['icon']] !!}
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</aside>

@push('scripts')
    @vite('resources/js/sidebar-submenu.js')
@endpush
