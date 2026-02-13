<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RemiseService
{
    public function __construct(private readonly EleveService $eleveService)
    {
    }

    /**
     * Ajoute une remise et renvoie le nouveau solde calculé.
     */
    public function addDiscount(int $studentId, array $data): array
    {
        return DB::transaction(function () use ($studentId, $data): array {
            $student = Student::query()->findOrFail($studentId);

            $discount = Discount::query()->create([
                'student_id' => $student->id,
                'school_year_id' => $student->school_year_id,
                'amount_cents' => $data['amount_cents'],
                'reason' => $data['reason'],
                'granted_at' => $data['granted_at'],
                'notes' => $data['notes'] ?? null,
            ]);

            return [
                'discount' => $discount,
                'balance' => $this->eleveService->calculateStudentBalance($student),
            ];
        });
    }

    /**
     * Historique des remises d'un élève.
     */
    public function getDiscountHistory(int $studentId): Collection
    {
        Student::query()->findOrFail($studentId);

        return Discount::query()
            ->where('student_id', $studentId)
            ->orderByDesc('granted_at')
            ->orderByDesc('id')
            ->get();
    }
}
