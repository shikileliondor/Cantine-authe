<?php

namespace App\Http\Controllers;

use App\Services\VersementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VersementController extends Controller
{
    public function __construct(private readonly VersementService $versementService)
    {
    }

    public function store(Request $request, int $studentId): JsonResponse
    {
        $validated = $request->validate([
            'amount_cents' => ['required', 'integer', 'min:1'],
            'paid_at' => ['required', 'date'],
            'period' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        return response()->json($this->versementService->addPayment($studentId, $validated), 201);
    }

    public function index(int $studentId): JsonResponse
    {
        return response()->json($this->versementService->getPaymentHistory($studentId));
    }
}
