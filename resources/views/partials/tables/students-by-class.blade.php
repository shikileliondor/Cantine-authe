<article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
        <h3 class="text-lg font-semibold text-slate-900">Liste des élèves et classe associée</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-5 py-3 text-left font-semibold text-slate-600 sm:px-6">Élève</th>
                    <th scope="col" class="px-5 py-3 text-left font-semibold text-slate-600 sm:px-6">Classe</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($students as $student)
                    <tr>
                        <td class="px-5 py-3 text-slate-700 sm:px-6">{{ $student->last_name }} {{ $student->first_name }}</td>
                        <td class="px-5 py-3 text-slate-600 sm:px-6">{{ $student->schoolClass?->name ?? 'Non attribuée' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-5 py-4 text-center text-slate-500 sm:px-6">Aucun élève disponible pour l'année active.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
