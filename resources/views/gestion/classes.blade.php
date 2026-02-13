@extends('layouts.app', ['title' => 'Gestion des classes | Cantine', 'headerTitle' => 'Gestion - Classes'])

@section('content')
    <section class="space-y-5" data-classes-app>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">CRUD des classes</h2>
                <p class="text-sm text-slate-500">Ajoutez, modifiez ou supprimez les classes et leur montant annuel.</p>
            </div>
            <button type="button" data-open-create class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Ajouter une classe</button>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3">Classe</th>
                            <th class="px-4 py-3">Montant à payer</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="classes-table" class="divide-y divide-slate-100">
                    @foreach ($classes as $class)
                        <tr data-id="{{ $class->id }}">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $class->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ number_format($class->canteen_amount_cents / 100, 0, ',', ' ') }} FCFA</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium" data-edit='@json($class)'>Modifier</button>
                                    <button class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700" data-delete="{{ $class->id }}">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div id="class-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 p-4">
        <div class="mx-auto mt-12 w-full max-w-lg rounded-2xl bg-white p-5">
            <h3 id="modal-title" class="text-lg font-semibold">Nouvelle classe</h3>
            <form id="class-form" class="mt-4 space-y-3">
                <input type="hidden" id="class-id">
                <div>
                    <label class="mb-1 block text-sm">Nom de la classe</label>
                    <input id="class-name" required class="w-full rounded-xl border-slate-300" />
                </div>
                <div>
                    <label class="mb-1 block text-sm">Montant à payer (FCFA)</label>
                    <input id="class-amount" type="number" min="0" required class="w-full rounded-xl border-slate-300" />
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm">Annuler</button>
                    <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('class-modal');
    const form = document.getElementById('class-form');
    const idInput = document.getElementById('class-id');
    const nameInput = document.getElementById('class-name');
    const amountInput = document.getElementById('class-amount');

    const openModal = (classData = null) => {
        modal.classList.remove('hidden');
        idInput.value = classData?.id || '';
        nameInput.value = classData?.name || '';
        amountInput.value = classData ? Math.round(classData.canteen_amount_cents / 100) : '';
        document.getElementById('modal-title').textContent = classData ? 'Modifier une classe' : 'Nouvelle classe';
    };

    document.querySelector('[data-open-create]').addEventListener('click', () => openModal());
    modal.querySelector('[data-close]').addEventListener('click', () => modal.classList.add('hidden'));

    document.querySelectorAll('[data-edit]').forEach((btn) => btn.addEventListener('click', () => openModal(JSON.parse(btn.dataset.edit))));

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = { name: nameInput.value, canteen_amount_cents: Number(amountInput.value) * 100 };
        const classId = idInput.value;
        const response = await fetch(classId ? `/api/classes/${classId}` : '/api/classes', {
            method: classId ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload),
        });

        if (response.ok) window.location.reload();
    });

    document.querySelectorAll('[data-delete]').forEach((btn) => btn.addEventListener('click', async () => {
        if (!confirm('Confirmer la suppression de cette classe ?')) return;
        const response = await fetch(`/api/classes/${btn.dataset.delete}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        });
        if (response.ok) btn.closest('tr')?.remove();
    }));
});
</script>
@endpush
