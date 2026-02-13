<?php

namespace App\Http\Controllers;

use App\Services\EleveService;
use App\Services\RemiseService;
use App\Services\VersementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $students = $this->eleveService->listStudents();

        $rows = $students->map(function ($student): array {
            $totals = $this->eleveService->calculateStudentBalance($student);

            return [
                'student' => $student,
                'totals' => $totals,
                'status' => $totals['remaining_cents'] === 0 ? 'Payé' : 'Non payé',
            ];
        });

        $selectedStudentId = $request->integer('student_id') ?: $rows->first()['student']->id ?? null;

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
            'selectedStudentId' => $selectedStudentId,
            'selectedProfile' => $selectedProfile,
            'history' => $history,
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
}
