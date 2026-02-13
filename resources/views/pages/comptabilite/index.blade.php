@extends('layouts.app', [
    'title' => $title,
    'headerTitle' => $headerTitle,
])

@php
    $formatMoney = static fn (int $cents): string => number_format($cents / 100, 2, ',', ' ') . ' FCFA';
@endphp

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('comptabilite.index') }}" class="grid gap-4 lg:grid-cols-[1fr_220px_auto] lg:items-end">
                <div>
                    <label for="student_id" class="mb-1 block text-sm font-medium text-slate-700">Choisir un élève</label>
                    <select id="student_id" name="student_id" class="js-student-select w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @foreach ($studentOptions as $row)
                            <option value="{{ $row['student']->id }}" @selected($selectedStudentId === $row['student']->id)>
                                {{ $row['student']->last_name }} {{ $row['student']->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Statut</label>
                    <select id="status" name="status" class="js-status-select w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="" @selected($statusFilter === '')>Tous</option>
                        <option value="Payé" @selected($statusFilter === 'Payé')>Payé</option>
                        <option value="Non payé" @selected($statusFilter === 'Non payé')>Non payé</option>
                    </select>
                </div>

                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Filtrer
                </button>
            </form>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('comptabilite.export.pdf', ['student_id' => $selectedStudentId, 'status' => $statusFilter]) }}" class="rounded-xl border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                    Export PDF
                </a>
                <a href="{{ route('comptabilite.export.excel', ['student_id' => $selectedStudentId, 'status' => $statusFilter]) }}" class="rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                    Export Excel
                </a>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Élève</th>
                            <th class="px-4 py-3 font-semibold">Classe</th>
                            <th class="px-4 py-3 font-semibold">Total dû</th>
                            <th class="px-4 py-3 font-semibold">Versements</th>
                            <th class="px-4 py-3 font-semibold">Remises</th>
                            <th class="px-4 py-3 font-semibold">Reste</th>
                            <th class="px-4 py-3 font-semibold">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rows as $row)
                            <tr class="cursor-pointer hover:bg-slate-50" onclick="window.location='{{ route('comptabilite.index', ['student_id' => $row['student']->id]) }}'">
                                <td class="px-4 py-3">{{ $row['student']->last_name }} {{ $row['student']->first_name }}</td>
                                <td class="px-4 py-3">{{ $row['student']->schoolClass->name }}</td>
                                <td class="px-4 py-3">{{ $formatMoney($row['totals']['expected_cents']) }}</td>
                                <td class="px-4 py-3">{{ $formatMoney($row['totals']['paid_cents']) }}</td>
                                <td class="px-4 py-3">{{ $formatMoney($row['totals']['discount_cents']) }}</td>
                                <td class="px-4 py-3">{{ $formatMoney($row['totals']['remaining_cents']) }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $row['status'] === 'Payé' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $row['status'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-slate-500">Aucun élève disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @if ($selectedProfile)
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ $selectedProfile['student']->last_name }} {{ $selectedProfile['student']->first_name }}
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" data-modal-target="payment-modal" class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">Ajouter versement</button>
                        <button type="button" data-modal-target="discount-modal" class="rounded-xl bg-slate-800 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-900">Faire remise</button>
                        <a href="#historique" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Voir historique</a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Total dû</p><p class="text-base font-semibold">{{ $formatMoney($selectedProfile['totals']['expected_cents']) }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Total versé</p><p class="text-base font-semibold">{{ $formatMoney($selectedProfile['totals']['paid_cents']) }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Total remise</p><p class="text-base font-semibold">{{ $formatMoney($selectedProfile['totals']['discount_cents']) }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Reste à payer</p><p class="text-base font-semibold">{{ $formatMoney($selectedProfile['totals']['remaining_cents']) }}</p></div>
                </div>

                <div id="historique" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-slate-600">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Date</th>
                                <th class="px-3 py-2 font-semibold">Type</th>
                                <th class="px-3 py-2 font-semibold">Détail</th>
                                <th class="px-3 py-2 font-semibold">Montant</th>
                                <th class="px-3 py-2 font-semibold">Statut</th>
                                <th class="px-3 py-2 font-semibold">Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($history as $line)
                                <tr>
                                    <td class="px-3 py-2">{{ $line['date']->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2">{{ $line['type'] }}</td>
                                    <td class="px-3 py-2">{{ $line['label'] }} <span class="text-xs text-slate-500">({{ $line['method'] }})</span></td>
                                    <td class="px-3 py-2">{{ $formatMoney($line['amount_cents']) }}</td>
                                    <td class="px-3 py-2">{{ $line['status'] }}</td>
                                    <td class="px-3 py-2 text-slate-500">{{ $line['notes'] ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-3 text-center text-slate-500">Aucun versement ou remise pour cet élève.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div id="payment-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
                <div class="w-full max-w-xl rounded-2xl bg-white p-5">
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-semibold">Ajouter un versement</h3><button type="button" data-modal-close>&times;</button></div>
                    <form method="POST" action="{{ route('comptabilite.payments.store', ['studentId' => $selectedProfile['student']->id]) }}" class="grid gap-3 md:grid-cols-2">
                        @csrf
                        <div><label class="text-sm">Montant (centimes)</label><input required name="amount_cents" type="number" min="1" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></div>
                        <div><label class="text-sm">Date</label><input required name="paid_at" type="date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></div>
                        <div>
                            <label class="text-sm" for="period">Période</label>
                            <select required id="period" name="period" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
                                <option value="" disabled selected>Choisir un mois</option>
                                <option value="Janvier">Janvier</option>
                                <option value="Février">Février</option>
                                <option value="Mars">Mars</option>
                                <option value="Avril">Avril</option>
                                <option value="Mai">Mai</option>
                                <option value="Juin">Juin</option>
                                <option value="Juillet">Juillet</option>
                                <option value="Août">Août</option>
                                <option value="Septembre">Septembre</option>
                                <option value="Octobre">Octobre</option>
                                <option value="Novembre">Novembre</option>
                                <option value="Décembre">Décembre</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm" for="payment_method">Mode de paiement</label>
                            <select required id="payment_method" name="payment_method" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
                                <option value="" disabled selected>Choisir un moyen de paiement</option>
                                <option value="Espèces">Espèces</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Virement bancaire">Virement bancaire</option>
                                <option value="Carte bancaire">Carte bancaire</option>
                                <option value="Chèque">Chèque</option>
                            </select>
                        </div>
                        <div class="md:col-span-2"><label class="text-sm">Note</label><textarea name="notes" rows="2" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></textarea></div>
                        <div class="md:col-span-2 flex justify-end gap-2"><button type="button" data-modal-close class="rounded-lg border border-slate-300 px-3 py-2">Annuler</button><button class="rounded-lg bg-indigo-600 px-3 py-2 text-white">Enregistrer</button></div>
                    </form>
                </div>
            </div>

            <div id="discount-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
                <div class="w-full max-w-xl rounded-2xl bg-white p-5">
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-semibold">Ajouter une remise</h3><button type="button" data-modal-close>&times;</button></div>
                    <form method="POST" action="{{ route('comptabilite.discounts.store', ['studentId' => $selectedProfile['student']->id]) }}" class="grid gap-3 md:grid-cols-2">
                        @csrf
                        <div><label class="text-sm">Montant (centimes)</label><input required name="amount_cents" type="number" min="1" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></div>
                        <div><label class="text-sm">Date</label><input required name="granted_at" type="date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></div>
                        <div class="md:col-span-2"><label class="text-sm">Motif</label><input required name="reason" type="text" placeholder="Ex: Aide sociale" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></div>
                        <div class="md:col-span-2"><label class="text-sm">Note</label><textarea name="notes" rows="2" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></textarea></div>
                        <div class="md:col-span-2 flex justify-end gap-2"><button type="button" data-modal-close class="rounded-lg border border-slate-300 px-3 py-2">Annuler</button><button class="rounded-lg bg-slate-800 px-3 py-2 text-white">Enregistrer</button></div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            $('.js-student-select').select2({ width: '100%' });
            $('.js-status-select').select2({ width: '100%', minimumResultsForSearch: Infinity });

            const openers = document.querySelectorAll('[data-modal-target]');
            const closers = document.querySelectorAll('[data-modal-close]');

            openers.forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.modalTarget);
                    modal?.classList.remove('hidden');
                    modal?.classList.add('flex');
                });
            });

            closers.forEach((button) => {
                button.addEventListener('click', () => {
                    button.closest('.fixed')?.classList.add('hidden');
                    button.closest('.fixed')?.classList.remove('flex');
                });
            });
        });
    </script>
@endpush
