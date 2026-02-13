@extends('layouts.app', [
    'title' => 'Dashboard | Cantine scolaire',
    'headerTitle' => 'Tableau de bord',
])

@section('content')
    <section class="space-y-6">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-indigo-600">Bienvenue</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Vue d'ensemble de la cantine</h2>
            <p class="mt-2 text-sm text-slate-500">Suivez les indicateurs clés pour les élèves, les classes et la comptabilité.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Élèves inscrits</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">248</p>
            </article>
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Classes actives</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">12</p>
            </article>
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Paiements reçus</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">3 450 €</p>
            </article>
            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Restes à payer</p>
                <p class="mt-2 text-3xl font-bold text-rose-600">980 €</p>
            </article>
        </div>

        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Évolution mensuelle des versements</h3>
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-600">Année en cours</span>
            </div>
            <div class="h-72">
                <canvas id="paymentsChart"></canvas>
            </div>
        </article>
    </section>
@endsection

@push('scripts')
    <script>
        const chartCanvas = document.getElementById('paymentsChart');

        if (chartCanvas && typeof Chart !== 'undefined') {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Versements (€)',
                        data: [350, 510, 430, 620, 580, 690],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
