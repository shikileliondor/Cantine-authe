@extends('layouts.app', [
    'title' => 'Gestion | Cantine scolaire',
    'headerTitle' => 'Gestion',
])

@section('content')
    <section class="space-y-6">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-indigo-600">Gestion scolaire</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Élèves et classes</h2>
            <p class="mt-2 text-sm text-slate-500">Liste des élèves avec leur classe associée.</p>
        </div>

        @include('gestion.partials.students-table', ['students' => $students])
    </section>
@endsection
