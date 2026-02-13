<article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Nom</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Prénom</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Classe</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($students as $student)
                    <tr>
                        <td class="px-4 py-3 text-slate-800">{{ $student->last_name }}</td>
                        <td class="px-4 py-3 text-slate-800">{{ $student->first_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->schoolClass?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">Aucun élève trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
