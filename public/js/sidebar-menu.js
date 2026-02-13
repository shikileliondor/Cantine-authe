document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const menuButton = document.getElementById('mobile-menu-button');

    const openSidebar = () => {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
        menuButton?.setAttribute('aria-expanded', 'true');
    };

    const closeSidebar = () => {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
        menuButton?.setAttribute('aria-expanded', 'false');
    };

    menuButton?.addEventListener('click', () => {
        const isHidden = sidebar?.classList.contains('-translate-x-full');
        if (isHidden) {
            openSidebar();
            return;
        }

        closeSidebar();
    });

    overlay?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            overlay?.classList.add('hidden');
            menuButton?.setAttribute('aria-expanded', 'false');
        }
    });

    document.querySelectorAll('[data-submenu]').forEach((submenu) => {
        const button = submenu.querySelector('[data-submenu-toggle]');
        const content = submenu.querySelector('[data-submenu-content]');
        const arrow = submenu.querySelector('[data-submenu-arrow]');

        const syncArrow = () => {
            const expanded = button?.getAttribute('aria-expanded') === 'true';
            arrow?.classList.toggle('rotate-180', expanded);
        };

        button?.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            content?.classList.toggle('hidden', expanded);
            syncArrow();
        });

        syncArrow();
    });
});
