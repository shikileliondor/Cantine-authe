<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authentification PIN | Cantine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 px-4 py-6 text-slate-100 sm:flex sm:items-center sm:justify-center">
    <main class="mx-auto w-full max-w-sm rounded-3xl border border-slate-800 bg-slate-900/95 p-6 shadow-2xl shadow-slate-950/60 sm:p-8">
        <div class="mb-6 text-center">
            <p class="text-sm uppercase tracking-[0.2em] text-indigo-300">Cantine</p>
            <h1 class="mt-2 text-2xl font-semibold text-white">Connexion sécurisée</h1>
            <p class="mt-2 text-sm text-slate-300">Entrez votre code PIN à 4 chiffres.</p>
        </div>

        <form method="POST" action="{{ route('pin.authenticate') }}" class="space-y-5" id="pin-form">
            @csrf

            <div>
                <label for="pin" class="sr-only">Code PIN</label>
                <div class="relative">
                    <input
                        id="pin"
                        name="pin"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="4"
                        autocomplete="one-time-code"
                        readonly
                        value="{{ old('pin', '') }}"
                        aria-describedby="pin-help"
                        class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-5 py-4 text-center text-3xl font-semibold tracking-[0.4em] text-white outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/35"
                    >
                    <button
                        id="toggle-pin"
                        type="button"
                        aria-pressed="false"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-medium text-slate-200 transition hover:bg-slate-800"
                    >
                        Afficher
                    </button>
                </div>
                <p id="pin-help" class="mt-2 text-xs text-slate-400">Les chiffres sont masqués par défaut pour préserver votre confidentialité.</p>
                @error('pin')
                    <p class="mt-3 rounded-xl border border-rose-500/40 bg-rose-500/10 px-3 py-2 text-sm text-rose-200">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-3" role="group" aria-label="Clavier numérique">
                @foreach ([1,2,3,4,5,6,7,8,9] as $digit)
                    <button type="button" class="pin-key rounded-2xl bg-slate-800 px-4 py-4 text-2xl font-semibold text-white shadow-lg shadow-slate-950/40 transition active:scale-95" data-key="{{ $digit }}">{{ $digit }}</button>
                @endforeach
                <button type="button" id="clear-pin" class="rounded-2xl border border-slate-600 bg-slate-900 px-4 py-4 text-sm font-semibold text-slate-200">Effacer</button>
                <button type="button" class="pin-key rounded-2xl bg-slate-800 px-4 py-4 text-2xl font-semibold text-white shadow-lg shadow-slate-950/40 transition active:scale-95" data-key="0">0</button>
                <button type="button" id="delete-digit" class="rounded-2xl border border-slate-600 bg-slate-900 px-4 py-4 text-sm font-semibold text-slate-200">⌫</button>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-indigo-500 px-4 py-4 text-base font-semibold text-white shadow-lg shadow-indigo-500/35 transition hover:bg-indigo-400">
                Valider
            </button>

            <div class="rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-slate-300">
                PIN oublié ? Demandez une <span class="font-semibold text-white">réinitialisation admin</span> depuis les paramètres ou contactez l'administrateur de l'école.
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pinInput = document.getElementById('pin');
            const form = document.getElementById('pin-form');
            const togglePin = document.getElementById('toggle-pin');
            const keypadButtons = document.querySelectorAll('.pin-key');
            const clearButton = document.getElementById('clear-pin');
            const deleteButton = document.getElementById('delete-digit');

            if (!pinInput || !form || !togglePin) {
                return;
            }

            const updateDisplayMode = (isVisible) => {
                pinInput.type = isVisible ? 'text' : 'password';
                togglePin.textContent = isVisible ? 'Masquer' : 'Afficher';
                togglePin.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
            };

            updateDisplayMode(false);

            const appendDigit = (digit) => {
                if (pinInput.value.length >= 4) {
                    return;
                }

                pinInput.value = `${pinInput.value}${digit}`;
            };

            keypadButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    appendDigit(button.dataset.key ?? '');
                });
            });

            clearButton?.addEventListener('click', () => {
                pinInput.value = '';
            });

            deleteButton?.addEventListener('click', () => {
                pinInput.value = pinInput.value.slice(0, -1);
            });

            togglePin.addEventListener('click', () => {
                const shouldShowPin = pinInput.type === 'password';
                updateDisplayMode(shouldShowPin);

                if (shouldShowPin) {
                    window.setTimeout(() => {
                        updateDisplayMode(false);
                    }, 3000);
                }
            });

            form.addEventListener('submit', (event) => {
                if (pinInput.value.length !== 4) {
                    event.preventDefault();
                    window.alert('Le PIN doit contenir exactement 4 chiffres.');
                }
            });
        });
    </script>
</body>
</html>
