@extends('layouts.app', [
    'title' => 'Gestion | Cantine scolaire',
    'headerTitle' => 'Gestion',
])

@section('content')
    <section class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Administration</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Gestion des classes et des élèves</h2>
            <p class="mt-2 text-sm text-slate-500">Choisissez un module pour ouvrir son CRUD complet.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <a href="{{ route('gestion.classes') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Classes</h3>
                    <span class="rounded-xl bg-indigo-50 p-2 text-indigo-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v4.5H3.75V4.5Zm0 10.5h7.5v4.5h-7.5V15Zm10.5-4.5h6v9h-6v-9Z"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $classesCount }}</p>
                <p class="mt-2 text-sm text-slate-500">Total des classes de l'année scolaire active.</p>
            </a>

            <a href="{{ route('gestion.students') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Élèves</h3>
                    <span class="rounded-xl bg-emerald-50 p-2 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75a17.933 17.933 0 0 1-7.499-1.632Z"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $studentsCount }}</p>
                <p class="mt-2 text-sm text-slate-500">Total des élèves (actifs et archivés) de l'année active.</p>
            </a>
        </div>
    </section>
@endsection
