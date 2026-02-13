<?php

namespace App\Http\Controllers;

use App\Services\EleveService;
use App\Services\RemiseService;
use App\Services\VersementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ComptabiliteController extends Controller
{
    public function __construct(
        private readonly EleveService $eleveService,
        private readonly VersementService $versementService,
        private readonly RemiseService $remiseService,
    ) {
    }

    public function index(Request $request): View
    {
        $allRows = $this->buildRowsWithStatus();
        $statusFilter = $request->string('status')->toString();
        $rows = $this->filterRowsByStatus($allRows, $statusFilter);

        $selectedStudentId = $request->integer('student_id') ?: $allRows->first()['student']->id ?? null;

        $selectedProfile = $selectedStudentId ? $this->eleveService->getStudentProfile($selectedStudentId) : null;

        $history = collect();

        if ($selectedProfile) {
            $payments = $selectedProfile['payments']->map(fn ($payment): array => [
                'date' => $payment->paid_at,
                'type' => 'Versement',
                'label' => $payment->period,
                'amount_cents' => $payment->amount_cents,
                'method' => $payment->payment_method,
                'status' => 'Comptabilisé',
                'notes' => $payment->notes,
            ]);

            $discounts = $selectedProfile['discounts']->map(fn ($discount): array => [
                'date' => $discount->granted_at,
                'type' => 'Remise',
                'label' => $discount->reason,
                'amount_cents' => $discount->amount_cents,
                'method' => 'N/A',
                'status' => 'Accordée',
                'notes' => $discount->notes,
            ]);

            $history = $payments
                ->merge($discounts)
                ->sortByDesc(fn (array $item) => $item['date'])
                ->values();
        }

        return view('pages.comptabilite.index', [
            'title' => 'Comptabilité',
            'headerTitle' => 'Comptabilité',
            'rows' => $rows,
            'studentOptions' => $allRows,
            'selectedStudentId' => $selectedStudentId,
            'selectedProfile' => $selectedProfile,
            'history' => $history,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function exportExcel(Request $request): Response
    {
        $rows = $this->buildRowsWithStatus();
        $filteredRows = $this->filterRowsByStatus($rows, $request->query('status'));

        $filename = 'comptabilite-export-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ];

        return response()->streamDownload(function () use ($filteredRows): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            fputs($stream, "\xEF\xBB\xBF");

            fputcsv($stream, ['Élève', 'Classe', 'Total dû', 'Versements', 'Remises', 'Reste', 'Statut'], ';');

            foreach ($filteredRows as $row) {
                fputcsv($stream, [
                    $row['student']->last_name . ' ' . $row['student']->first_name,
                    $row['student']->schoolClass->name,
                    number_format($row['totals']['expected_cents'] / 100, 2, ',', ' '),
                    number_format($row['totals']['paid_cents'] / 100, 2, ',', ' '),
                    number_format($row['totals']['discount_cents'] / 100, 2, ',', ' '),
                    number_format($row['totals']['remaining_cents'] / 100, 2, ',', ' '),
                    $row['status'],
                ], ';');
            }

            fclose($stream);
        }, $filename, $headers);
    }

    public function exportPdf(Request $request): Response
    {
        $rows = $this->buildRowsWithStatus();
        $filteredRows = $this->filterRowsByStatus($rows, $request->query('status'));

        $statusLabel = $request->string('status')->toString() ?: 'Tous';

        $content = view('pages.comptabilite.exports.pdf', [
            'rows' => $filteredRows,
            'statusLabel' => $statusLabel,
            'generatedAt' => Carbon::now(),
        ])->render();

        $filename = 'comptabilite-export-' . now()->format('Ymd_His') . '.html';

        return response($content, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }

    public function storePayment(Request $request, int $studentId): RedirectResponse
    {
        $validated = $request->validate([
            'amount_cents' => ['required', 'integer', 'min:1'],
            'paid_at' => ['required', 'date'],
            'period' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->versementService->addPayment($studentId, $validated);

        return redirect()
            ->route('comptabilite.index', ['student_id' => $studentId])
            ->with('status', 'Versement enregistré avec succès.');
    }

    public function storeDiscount(Request $request, int $studentId): RedirectResponse
    {
        $validated = $request->validate([
            'amount_cents' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
            'granted_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->remiseService->addDiscount($studentId, $validated);

        return redirect()
            ->route('comptabilite.index', ['student_id' => $studentId])
            ->with('status', 'Remise enregistrée avec succès.');
    }

    private function buildRowsWithStatus(): Collection
    {
        $students = $this->eleveService->listStudents();

        return $students->map(function ($student): array {
            $totals = $this->eleveService->calculateStudentBalance($student);

            return [
                'student' => $student,
                'totals' => $totals,
                'status' => $totals['remaining_cents'] === 0 ? 'Payé' : 'Non payé',
            ];
        });
    }

    private function filterRowsByStatus(Collection $rows, ?string $status): Collection
    {
        if (!in_array($status, ['Payé', 'Non payé'], true)) {
            return $rows;
        }

        return $rows->where('status', $status)->values();
    }
}
