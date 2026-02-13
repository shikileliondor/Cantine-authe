<?php

namespace App\Services;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class YearService
{
    /**
     * Retourne l'année active si elle existe.
     */
    public function getActiveYearOrNull(): ?SchoolYear
    {
        return SchoolYear::query()->active()->first();
    }

    /**
     * Crée une année scolaire.
     */
    public function createYear(array $data): SchoolYear
    {
        $shouldActivate = (bool) ($data['is_active'] ?? false);

        if (! $this->getActiveYearOrNull()) {
            $shouldActivate = true;
        }

        return SchoolYear::query()->create([
            'name' => $data['name'],
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
            'is_active' => $shouldActivate,
        ]);
    }

    /**
     * Retourne l'année active utilisée automatiquement par l'application.
     */
    public function getActiveYear(): SchoolYear
    {
        $activeYear = $this->getActiveYearOrNull();

        if (! $activeYear) {
            throw new RuntimeException('Aucune année scolaire active disponible.');
        }

        return $activeYear;
    }

    /**
     * Définit une année comme active (une seule active à la fois).
     */
    public function setActiveYear(int $yearId): SchoolYear
    {
        return DB::transaction(function () use ($yearId): SchoolYear {
            $year = SchoolYear::query()->findOrFail($yearId);
            $year->update(['is_active' => true]);

            return $year->fresh();
        });
    }

    /**
     * Archive une année scolaire.
     */
    public function archiveYear(int $yearId): SchoolYear
    {
        $year = SchoolYear::query()->findOrFail($yearId);

        if ($year->is_active) {
            throw new RuntimeException('Impossible d\'archiver une année active. Activez une autre année avant.');
        }

        $year->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        return $year->fresh();
    }

    /**
     * Liste des années archivées.
     */
    public function getArchivedYears(): Collection
    {
        return SchoolYear::query()
            ->where('is_archived', true)
            ->orderByDesc('archived_at')
            ->get();
    }

    /**
     * Liste de toutes les années.
     */
    public function getAllYears(): Collection
    {
        return SchoolYear::query()->orderByDesc('starts_on')->get();
    }
}
