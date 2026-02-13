@extends('layouts.app', [
    'title' => 'Gestion | Cantine scolaire',
    'headerTitle' => 'Gestion',
])

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
            <p class="text-sm font-medium uppercase tracking-wide text-indigo-600">École</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Gestion des élèves et des classes</h2>
            <p class="mt-2 text-sm text-slate-500">Toutes les données affichées proviennent des services applicatifs.</p>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <article id="eleves" class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Élèves</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $students->count() }}</p>
                </article>
                <article id="classes" class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Classes</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $classes->count() }}</p>
                </article>
            </div>
        </div>

        @include('partials.tables.students-by-class', ['students' => $students])
    </section>
@endsection
