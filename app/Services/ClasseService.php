<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class ClasseService
{
    public function __construct(private readonly YearService $yearService)
    {
    }

    /**
     * Liste les classes d'une année (année active par défaut).
     */
    public function listClasses(?int $yearId = null): Collection
    {
        $selectedYearId = $yearId ?? $this->yearService->getActiveYear()->id;

        return SchoolClass::query()
            ->with('schoolYear')
            ->where('school_year_id', $selectedYearId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Crée une classe dans l'année active (ou une année spécifique).
     */
    public function createClass(array $data): SchoolClass
    {
        $selectedYearId = $data['school_year_id'] ?? $this->yearService->getActiveYear()->id;
        $this->ensureYearNotArchived($selectedYearId);

        return SchoolClass::query()->create([
            'school_year_id' => $selectedYearId,
            'name' => $data['name'],
            'canteen_amount_cents' => $data['canteen_amount_cents'],
        ]);
    }

    /**
     * Détail d'une classe.
     */
    public function getClass(int $classId): SchoolClass
    {
        return SchoolClass::query()->with(['schoolYear', 'students'])->findOrFail($classId);
    }

    /**
     * Met à jour une classe (interdit si année archivée).
     */
    public function updateClass(int $classId, array $data): SchoolClass
    {
        $class = SchoolClass::query()->findOrFail($classId);
        $this->ensureYearNotArchived($class->school_year_id);

        $class->update([
            'name' => $data['name'] ?? $class->name,
            'canteen_amount_cents' => $data['canteen_amount_cents'] ?? $class->canteen_amount_cents,
        ]);

        return $class->fresh(['schoolYear']);
    }

    /**
     * Supprime une classe (interdit si année archivée).
     */
    public function deleteClass(int $classId): void
    {
        $class = SchoolClass::query()->findOrFail($classId);
        $this->ensureYearNotArchived($class->school_year_id);

        $class->delete();
    }

    private function ensureYearNotArchived(int $yearId): void
    {
        $year = SchoolYear::query()->findOrFail($yearId);

        if ($year->is_archived) {
            throw new RuntimeException('Action impossible : cette classe appartient à une année archivée.');
        }
    }
}
