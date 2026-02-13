<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Cantine scolaire' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
    <div class="min-h-screen lg:pl-72">
        @include('partials.sidebar')

        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/50 lg:hidden"></div>

        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
            <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button
                        id="mobile-menu-button"
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 p-2 text-slate-700 transition hover:bg-slate-100 lg:hidden"
                        aria-controls="app-sidebar"
                        aria-expanded="false"
                        aria-label="Ouvrir le menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-900 sm:text-xl">{{ $headerTitle ?? 'Tableau de bord' }}</h1>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuButton = document.getElementById('mobile-menu-button');

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                menuButton.setAttribute('aria-expanded', 'true');
            };

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                menuButton.setAttribute('aria-expanded', 'false');
            };

            menuButton?.addEventListener('click', () => {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            overlay?.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    menuButton.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
