<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class EleveService
{
    public function __construct(private readonly YearService $yearService)
    {
    }

    /**
     * Liste les élèves de l'année active (ou d'une année spécifique).
     */
    public function listStudents(?int $yearId = null): Collection
    {
        $selectedYearId = $yearId ?? $this->yearService->getActiveYear()->id;

        return Student::query()
            ->with('schoolClass')
            ->where('school_year_id', $selectedYearId)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Crée un élève et le rattache automatiquement à l'année active.
     */
    public function createStudent(array $data): Student
    {
        $activeYear = $this->yearService->getActiveYear();

        $class = SchoolClass::query()->findOrFail($data['school_class_id']);

        return Student::query()->create([
            'school_year_id' => $activeYear->id,
            'school_class_id' => $class->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'] ?? null,
            'guardian_name' => $data['guardian_name'],
            'guardian_phone' => $data['guardian_phone'],
        ]);
    }

    /**
     * Met à jour les informations d'un élève.
     */
    public function updateStudent(int $studentId, array $data): Student
    {
        $student = Student::query()->findOrFail($studentId);

        if (isset($data['school_class_id'])) {
            $newClass = SchoolClass::query()->findOrFail($data['school_class_id']);
            $data['school_year_id'] = $newClass->school_year_id;
        }

        $student->update([
            'school_class_id' => $data['school_class_id'] ?? $student->school_class_id,
            'school_year_id' => $data['school_year_id'] ?? $student->school_year_id,
            'first_name' => $data['first_name'] ?? $student->first_name,
            'last_name' => $data['last_name'] ?? $student->last_name,
            'birth_date' => $data['birth_date'] ?? $student->birth_date,
            'guardian_name' => $data['guardian_name'] ?? $student->guardian_name,
            'guardian_phone' => $data['guardian_phone'] ?? $student->guardian_phone,
        ]);

        return $student->fresh(['schoolClass', 'schoolYear']);
    }

    /**
     * Détail d'un élève + historique versements/remises + calculs financiers.
     */
    public function getStudentProfile(int $studentId): array
    {
        $student = Student::query()->with(['schoolClass', 'schoolYear'])->findOrFail($studentId);

        $payments = Payment::query()
            ->where('student_id', $student->id)
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get();

        $discounts = Discount::query()
            ->where('student_id', $student->id)
            ->orderByDesc('granted_at')
            ->orderByDesc('id')
            ->get();

        $totals = $this->calculateStudentBalance($student);

        return [
            'student' => $student,
            'payments' => $payments,
            'discounts' => $discounts,
            'totals' => $totals,
        ];
    }

    /**
     * Supprime (soft delete) un élève.
     */
    public function deleteStudent(int $studentId): void
    {
        Student::query()->findOrFail($studentId)->delete();
    }

    /**
     * Calcule le solde d'un élève.
     */
    public function calculateStudentBalance(Student $student): array
    {
        $student->loadMissing('schoolClass');

        $expected = (int) $student->schoolClass->canteen_amount_cents;
        $paid = (int) Payment::query()->where('student_id', $student->id)->sum('amount_cents');
        $discount = (int) Discount::query()->where('student_id', $student->id)->sum('amount_cents');

        return [
            'expected_cents' => $expected,
            'paid_cents' => $paid,
            'discount_cents' => $discount,
            'remaining_cents' => max($expected - $paid - $discount, 0),
        ];
    }
}
