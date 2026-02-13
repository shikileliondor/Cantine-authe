<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer mon PIN | Cantine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 px-4 py-6 text-slate-100 sm:flex sm:items-center sm:justify-center">
    <main class="mx-auto w-full max-w-sm rounded-3xl border border-slate-800 bg-slate-900/95 p-6 shadow-2xl shadow-slate-950/60 sm:p-8">
        <div class="mb-6 text-center">
            <p class="text-sm uppercase tracking-[0.2em] text-indigo-300">Cantine</p>
            <h1 class="mt-2 text-2xl font-semibold text-white">Créer / Réinitialiser le PIN</h1>
            <p class="mt-2 text-sm text-slate-300">Saisissez le code admin puis votre nouveau PIN à 4 chiffres.</p>
        </div>

        <form method="POST" action="{{ route('pin.setup.store') }}" class="space-y-4" id="pin-setup-form">
            @csrf

            <div>
                <label for="admin_code" class="mb-1 block text-sm font-medium text-slate-200">Code admin</label>
                <input id="admin_code" name="admin_code" type="password" autocomplete="off" value="{{ old('admin_code', '') }}" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/35">
                @error('admin_code')
                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_pin" class="mb-1 block text-sm font-medium text-slate-200">Nouveau PIN (4 chiffres)</label>
                <input id="new_pin" name="new_pin" inputmode="numeric" pattern="[0-9]*" maxlength="4" type="password" autocomplete="off" value="{{ old('new_pin', '') }}" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/35">
                @error('new_pin')
                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_pin_confirmation" class="mb-1 block text-sm font-medium text-slate-200">Confirmer le PIN</label>
                <input id="new_pin_confirmation" name="new_pin_confirmation" inputmode="numeric" pattern="[0-9]*" maxlength="4" type="password" autocomplete="off" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/35">
            </div>

            <div class="flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-200">
                <input id="show-pins" type="checkbox" class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-indigo-500">
                <label for="show-pins">Afficher temporairement les champs PIN</label>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-indigo-500 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-500/35 transition hover:bg-indigo-400">Enregistrer mon PIN</button>

            <a href="{{ route('pin.login') }}" class="block text-center text-sm text-slate-300 underline">Retour à la connexion</a>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('show-pins');
            const pinInputs = [document.getElementById('new_pin'), document.getElementById('new_pin_confirmation')];
            const form = document.getElementById('pin-setup-form');

            toggle?.addEventListener('change', () => {
                const reveal = toggle.checked;
                pinInputs.forEach((input) => {
                    if (input) {
                        input.type = reveal ? 'text' : 'password';
                    }
                });

                if (reveal) {
                    window.setTimeout(() => {
                        toggle.checked = false;
                        pinInputs.forEach((input) => {
                            if (input) {
                                input.type = 'password';
                            }
                        });
                    }, 3000);
                }
            });

            form?.addEventListener('submit', (event) => {
                const pin = document.getElementById('new_pin')?.value ?? '';
                const pinConfirmation = document.getElementById('new_pin_confirmation')?.value ?? '';

                if (!/^\d{4}$/.test(pin) || !/^\d{4}$/.test(pinConfirmation)) {
                    event.preventDefault();
                    window.alert('Le nouveau PIN et sa confirmation doivent contenir exactement 4 chiffres.');
                }
            });
        });
    </script>
</body>
</html>
