<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Payment;
use App\Models\Student;

class DashboardService
{
    public function __construct(private readonly YearService $yearService)
    {
    }

    /**
     * KPIs globaux du tableau de bord de l'année active.
     */
    public function getDashboardMetrics(): array
    {
        $year = $this->yearService->getActiveYear();

        $studentsCount = Student::query()->where('school_year_id', $year->id)->count();

        $expectedTotal = Student::query()
            ->where('school_year_id', $year->id)
            ->join('school_classes', 'school_classes.id', '=', 'students.school_class_id')
            ->sum('school_classes.canteen_amount_cents');

        $paidTotal = Payment::query()->where('school_year_id', $year->id)->sum('amount_cents');
        $discountTotal = Discount::query()->where('school_year_id', $year->id)->sum('amount_cents');

        return [
            'active_year' => $year,
            'total_students' => (int) $studentsCount,
            'expected_total_cents' => (int) $expectedTotal,
            'paid_total_cents' => (int) $paidTotal,
            'discount_total_cents' => (int) $discountTotal,
            'remaining_total_cents' => max((int) $expectedTotal - (int) $paidTotal - (int) $discountTotal, 0),
        ];
    }

    /**
     * Données simples pour graphiques (encaissements/remises par mois).
     */
    public function getChartData(): array
    {
        $year = $this->yearService->getActiveYear();

        $paymentsByMonth = Payment::query()
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount_cents) as total")
            ->where('school_year_id', $year->id)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $discountsByMonth = Discount::query()
            ->selectRaw("DATE_FORMAT(granted_at, '%Y-%m') as month, SUM(amount_cents) as total")
            ->where('school_year_id', $year->id)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return [
            'payments_by_month' => $paymentsByMonth,
            'discounts_by_month' => $discountsByMonth,
        ];
    }
}
