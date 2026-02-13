@extends('layouts.app', [
    'title' => 'Paramètres | Cantine scolaire',
    'headerTitle' => 'Paramètres globaux',
])

@section('content')
    <section class="space-y-6">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-indigo-600">Paramètres globaux</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Année scolaire active</h2>
            <p class="mt-2 text-sm text-slate-500">Définissez ici l'année scolaire active utilisée automatiquement dans tous les écrans.</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
            <h3 class="text-lg font-semibold text-slate-900">Créer une année scolaire</h3>

            <form action="{{ route('settings.years.store') }}" method="POST" class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div>
                    <label class="text-sm text-slate-700" for="name">Libellé</label>
                    <input id="name" name="name" type="text" required placeholder="Ex: 2026-2027" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                </div>
                <div>
                    <label class="text-sm text-slate-700" for="starts_on">Date de début</label>
                    <input id="starts_on" name="starts_on" type="date" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                </div>
                <div>
                    <label class="text-sm text-slate-700" for="ends_on">Date de fin</label>
                    <input id="ends_on" name="ends_on" type="date" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                </div>
                <div class="flex items-end">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" />
                        Définir comme année active
                    </label>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Créer l'année</button>
                </div>
            </form>
        </article>

        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
            <h3 class="text-lg font-semibold text-slate-900">Années existantes</h3>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-3 py-2 font-semibold">Nom</th>
                            <th class="px-3 py-2 font-semibold">Période</th>
                            <th class="px-3 py-2 font-semibold">Statut</th>
                            <th class="px-3 py-2 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($years as $year)
                            <tr>
                                <td class="px-3 py-2 font-medium text-slate-900">{{ $year->name }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ $year->starts_on->format('d/m/Y') }} - {{ $year->ends_on->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">
                                    @if ($year->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">Active</span>
                                    @elseif ($year->is_archived)
                                        <span class="rounded-full bg-slate-200 px-2 py-1 text-xs font-medium text-slate-700">Archivée</span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex justify-end gap-2">
                                        @unless ($year->is_active)
                                            <form action="{{ route('settings.years.activate', $year->id) }}" method="POST">
                                                @csrf
                                                <button class="rounded-lg border border-indigo-300 px-3 py-1.5 text-xs font-medium text-indigo-700">Activer</button>
                                            </form>
                                        @endunless

                                        @if (! $year->is_active && ! $year->is_archived)
                                            <form action="{{ route('settings.years.archive', $year->id) }}" method="POST">
                                                @csrf
                                                <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700">Archiver</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-slate-500">Aucune année scolaire enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($activeYear)
                <p class="mt-4 text-sm text-slate-600">Année active actuelle : <span class="font-semibold text-slate-900">{{ $activeYear->name }}</span></p>
            @endif
        </article>
    </section>
@endsection
