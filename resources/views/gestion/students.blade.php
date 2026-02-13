@extends('layouts.app', ['title' => 'Gestion des élèves | Cantine', 'headerTitle' => 'Gestion - Élèves'])

@section('content')
<section class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">CRUD des élèves</h2>
            <p class="text-sm text-slate-500">Ajout, modification, archivage et restauration.</p>
        </div>
        <button type="button" data-open-create class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Ajouter un élève</button>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-slate-700">
                    <tr>
                        <th class="px-4 py-3">Nom complet</th>
                        <th class="px-4 py-3">Classe</th>
                        <th class="px-4 py-3">Responsable</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach ($students as $student)
                    <tr data-id="{{ $student->id }}">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $student->last_name }} {{ $student->first_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->schoolClass?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->guardian_name }} ({{ $student->guardian_phone }})</td>
                        <td class="px-4 py-3">{!! $student->deleted_at ? '<span class="rounded-full bg-amber-100 px-2 py-1 text-xs text-amber-700">Archivé</span>' : '<span class="rounded-full bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Actif</span>' !!}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('gestion.students.show', $student->id) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium">Fiche</a>
                                <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium" data-edit='@json($student)'>Modifier</button>
                                @if ($student->deleted_at)
                                    <button class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700" data-restore="{{ $student->id }}">Restaurer</button>
                                @else
                                    <button class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700" data-archive="{{ $student->id }}">Archiver</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<div id="student-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 p-4">
    <div class="mx-auto mt-8 w-full max-w-xl rounded-2xl bg-white p-5">
        <h3 id="student-modal-title" class="text-lg font-semibold">Nouvel élève</h3>
        <form id="student-form" class="mt-4 grid gap-3 sm:grid-cols-2">
            <input type="hidden" id="student-id">
            <div><label class="text-sm">Nom</label><input id="last-name" required class="mt-1 w-full rounded-xl border-slate-300"/></div>
            <div><label class="text-sm">Prénom</label><input id="first-name" required class="mt-1 w-full rounded-xl border-slate-300"/></div>
            <div><label class="text-sm">Date de naissance</label><input id="birth-date" type="date" class="mt-1 w-full rounded-xl border-slate-300"/></div>
            <div>
                <label class="text-sm">Classe</label>
                <select id="school-class" required class="mt-1 w-full rounded-xl border-slate-300">
                    <option value="">Choisir</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="text-sm">Nom du responsable</label><input id="guardian-name" required class="mt-1 w-full rounded-xl border-slate-300"/></div>
            <div><label class="text-sm">Téléphone du responsable</label><input id="guardian-phone" required class="mt-1 w-full rounded-xl border-slate-300"/></div>
            <div class="sm:col-span-2 flex justify-end gap-2 pt-2">
                <button type="button" data-close-student class="rounded-xl border border-slate-300 px-4 py-2 text-sm">Annuler</button>
                <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('student-modal');
    const openBtn = document.querySelector('[data-open-create]');
    const closeBtn = document.querySelector('[data-close-student]');
    const form = document.getElementById('student-form');

    const fields = {
        id: document.getElementById('student-id'),
        school_class_id: document.getElementById('school-class'),
        first_name: document.getElementById('first-name'),
        last_name: document.getElementById('last-name'),
        birth_date: document.getElementById('birth-date'),
        guardian_name: document.getElementById('guardian-name'),
        guardian_phone: document.getElementById('guardian-phone'),
    };

    const openModal = (student = null) => {
        modal.classList.remove('hidden');
        document.getElementById('student-modal-title').textContent = student ? 'Modifier un élève' : 'Nouvel élève';
        fields.id.value = student?.id || '';
        fields.school_class_id.value = student?.school_class_id || '';
        fields.first_name.value = student?.first_name || '';
        fields.last_name.value = student?.last_name || '';
        fields.birth_date.value = student?.birth_date || '';
        fields.guardian_name.value = student?.guardian_name || '';
        fields.guardian_phone.value = student?.guardian_phone || '';
    };

    openBtn.addEventListener('click', () => openModal());
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

    document.querySelectorAll('[data-edit]').forEach((btn) => btn.addEventListener('click', () => openModal(JSON.parse(btn.dataset.edit))));

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = Object.fromEntries(Object.entries(fields).filter(([key]) => key !== 'id').map(([key, el]) => [key, el.value]));
        const studentId = fields.id.value;
        const response = await fetch(studentId ? `/api/eleves/${studentId}` : '/api/eleves', {
            method: studentId ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload),
        });

        if (response.ok) window.location.reload();
    });

    document.querySelectorAll('[data-archive]').forEach((btn) => btn.addEventListener('click', async () => {
        if (!confirm('Confirmer l\'archivage de cet élève ?')) return;
        const response = await fetch(`/api/eleves/${btn.dataset.archive}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        if (response.ok) window.location.reload();
    }));

    document.querySelectorAll('[data-restore]').forEach((btn) => btn.addEventListener('click', async () => {
        const response = await fetch(`/api/eleves/${btn.dataset.restore}/restore`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        if (response.ok) window.location.reload();
    }));
});
</script>
@endpush
