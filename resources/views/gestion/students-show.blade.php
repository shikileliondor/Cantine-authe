@extends('layouts.app', ['title' => 'Fiche élève | Cantine', 'headerTitle' => 'Fiche élève'])

@section('content')
@php
    $format = fn (int $cents): string => number_format($cents / 100, 0, ',', ' ') . ' FCFA';
@endphp
<section class="space-y-6">
    <a href="{{ route('gestion.students') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700">← Retour aux élèves</a>

    <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-bold text-slate-900">{{ $student->last_name }} {{ $student->first_name }}</h2>
        <div class="mt-3 grid gap-2 text-sm text-slate-600 sm:grid-cols-2">
            <p><span class="font-medium text-slate-800">Classe :</span> {{ $student->schoolClass?->name ?? '—' }}</p>
            <p><span class="font-medium text-slate-800">Année :</span> {{ $student->schoolYear?->label ?? '—' }}</p>
            <p><span class="font-medium text-slate-800">Date de naissance :</span> {{ optional($student->birth_date)->format('d/m/Y') ?? '—' }}</p>
            <p><span class="font-medium text-slate-800">Responsable :</span> {{ $student->guardian_name }} ({{ $student->guardian_phone }})</p>
        </div>
    </article>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-500">Montant à payer</p><p class="mt-2 text-xl font-bold">{{ $format($totals['expected_cents']) }}</p></article>
        <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-500">Total versé</p><p class="mt-2 text-xl font-bold text-emerald-600">{{ $format($totals['paid_cents']) }}</p></article>
        <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-500">Remises</p><p class="mt-2 text-xl font-bold text-indigo-600">{{ $format($totals['discount_cents']) }}</p></article>
        <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-500">Reste à payer</p><p class="mt-2 text-xl font-bold text-rose-600">{{ $format($totals['remaining_cents']) }}</p></article>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold">Historique des paiements</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                @forelse ($payments as $payment)
                    <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                        <span>{{ $payment->paid_at->format('d/m/Y') }}</span>
                        <span class="font-semibold text-emerald-700">{{ $format($payment->amount_cents) }}</span>
                    </li>
                @empty
                    <li class="text-slate-500">Aucun versement enregistré.</li>
                @endforelse
            </ul>
        </article>

        <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold">Historique des remises</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                @forelse ($discounts as $discount)
                    <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                        <span>{{ $discount->granted_at->format('d/m/Y') }}</span>
                        <span class="font-semibold text-indigo-700">{{ $format($discount->amount_cents) }}</span>
                    </li>
                @empty
                    <li class="text-slate-500">Aucune remise enregistrée.</li>
                @endforelse
            </ul>
        </article>
    </div>
</section>
@endsection
