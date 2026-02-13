<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authentification PIN | Cantine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="mx-auto flex min-h-screen w-full max-w-sm items-center justify-center px-5 py-8">
        <section class="w-full rounded-3xl border border-slate-700/70 bg-gradient-to-b from-slate-900 to-indigo-950 px-7 py-10 shadow-2xl shadow-black/40">
            <div class="text-center">
                <h1 class="text-[26px] font-light uppercase tracking-[0.08em] text-white">
                    Enter <span class="font-semibold text-slate-100">PIN</span> Code
                </h1>
                <div class="mt-4 flex justify-center gap-3" aria-hidden="true">
                    <span class="block h-[2px] w-8 rounded bg-slate-300/70"></span>
                    <span class="block h-[2px] w-8 rounded bg-slate-300/70"></span>
                    <span class="block h-[2px] w-8 rounded bg-slate-300/70"></span>
                    <span class="block h-[2px] w-8 rounded bg-slate-300/70"></span>
                </div>
                <p class="mt-4 text-xs uppercase tracking-[0.15em] text-slate-300">3 tentatives restantes</p>
            </div>

            <form method="POST" action="{{ route('pin.authenticate') }}" class="mt-8" id="pin-form">
                @csrf

                @if (session('status'))
                    <p class="mb-4 rounded-lg border border-emerald-400/30 bg-emerald-500/15 px-3 py-2 text-center text-sm text-emerald-100">{{ session('status') }}</p>
                @endif

                <label for="pin" class="sr-only">Code PIN</label>
                <input
                    id="pin"
                    name="pin"
                    type="password"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="4"
                    autocomplete="one-time-code"
                    readonly
                    value="{{ old('pin', '') }}"
                    class="sr-only"
                >

                @error('pin')
                    <p class="mb-4 rounded-lg border border-rose-400/30 bg-rose-500/15 px-3 py-2 text-center text-sm text-rose-100">{{ $message }}</p>
                @enderror

                <div class="mx-auto grid w-fit grid-cols-3 gap-3" role="group" aria-label="Clavier numérique">
                    @foreach ([1,2,3,4,5,6,7,8,9] as $digit)
                        <button
                            type="button"
                            class="pin-key h-12 w-12 rounded-lg border border-white/10 bg-slate-600/70 text-xl font-semibold text-white shadow-lg shadow-black/20 transition hover:bg-slate-500/80 active:scale-95"
                            data-key="{{ $digit }}"
                        >
                            {{ $digit }}
                        </button>
                    @endforeach
                    <span aria-hidden="true"></span>
                    <button
                        type="button"
                        class="pin-key h-12 w-12 rounded-lg border border-white/10 bg-slate-600/70 text-xl font-semibold text-white shadow-lg shadow-black/20 transition hover:bg-slate-500/80 active:scale-95"
                        data-key="0"
                    >
                        0
                    </button>
                    <button
                        type="button"
                        id="delete-digit"
                        class="h-12 w-12 rounded-lg border border-white/10 bg-slate-700/70 text-base font-semibold text-white transition hover:bg-slate-600/80 active:scale-95"
                    >
                        ⌫
                    </button>
                </div>

                <button type="submit" class="sr-only">Valider</button>
            </form>

            <div class="mt-6 text-center">
                <a
                    href="{{ route('pin.setup') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-indigo-300/40 bg-indigo-500/15 px-4 py-2 text-sm font-medium text-indigo-100 transition hover:bg-indigo-500/25"
                >
                    Créer / réinitialiser mon mot de passe
                </a>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pinInput = document.getElementById('pin');
            const form = document.getElementById('pin-form');
            const keypadButtons = document.querySelectorAll('.pin-key');
            const deleteButton = document.getElementById('delete-digit');

            if (!pinInput || !form) {
                return;
            }

            const appendDigit = (digit) => {
                if (pinInput.value.length >= 4) {
                    return;
                }

                pinInput.value = `${pinInput.value}${digit}`;

                if (pinInput.value.length === 4) {
                    form.submit();
                }
            };

            keypadButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    appendDigit(button.dataset.key ?? '');
                });
            });

            deleteButton?.addEventListener('click', () => {
                pinInput.value = pinInput.value.slice(0, -1);
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
