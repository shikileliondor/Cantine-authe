<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VersementService
{
    public function __construct(private readonly EleveService $eleveService)
    {
    }

    /**
     * Ajoute un versement et renvoie le nouveau solde calculé.
     */
    public function addPayment(int $studentId, array $data): array
    {
        return DB::transaction(function () use ($studentId, $data): array {
            $student = Student::query()->findOrFail($studentId);

            $payment = Payment::query()->create([
                'student_id' => $student->id,
                'school_year_id' => $student->school_year_id,
                'amount_cents' => $data['amount_cents'],
                'paid_at' => $data['paid_at'],
                'period' => $data['period'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            return [
                'payment' => $payment,
                'balance' => $this->eleveService->calculateStudentBalance($student),
            ];
        });
    }

    /**
     * Historique des versements d'un élève.
     */
    public function getPaymentHistory(int $studentId): Collection
    {
        Student::query()->findOrFail($studentId);

        return Payment::query()
            ->where('student_id', $studentId)
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get();
    }
}
