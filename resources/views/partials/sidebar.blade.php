@php
    $menuItems = [
        [
            'label' => 'Accueil',
            'route' => 'dashboard',
            'href' => Route::has('dashboard') ? route('dashboard') : '#',
            'icon' => 'home',
        ],
        [
            'label' => 'Élèves',
            'route' => 'eleves.index',
            'href' => Route::has('eleves.index') ? route('eleves.index') : '#',
            'icon' => 'students',
        ],
        [
            'label' => 'Classes',
            'route' => 'classes.index',
            'href' => Route::has('classes.index') ? route('classes.index') : '#',
            'icon' => 'classes',
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
        'students' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.742-.479 3 3 0 0 0-4.682-2.72m.94 3.198v.002c0 .26-.157.488-.383.563A11.953 11.953 0 0 1 12 21c-2.51 0-4.847-.776-6.778-2.099a.6.6 0 0 1-.383-.563v-.001m15.164 0a9.013 9.013 0 0 1-1.455-1.425m-13.09 1.425a9.013 9.013 0 0 0 1.455-1.425m0 0A2.999 2.999 0 0 1 12 13.5a2.999 2.999 0 0 1 2.892 3.412m-5.784 0a2.999 2.999 0 0 0 5.784 0M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        'classes' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 9.75h16.5M3.75 14.25h16.5M3.75 18.75h16.5" />',
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
            @foreach ($menuItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp

                <a
                    href="{{ $item['href'] }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        {!! $icons[$item['icon']] !!}
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
