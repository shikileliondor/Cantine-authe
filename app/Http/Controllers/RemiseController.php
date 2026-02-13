<?php

namespace App\Http\Controllers;

use App\Services\RemiseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RemiseController extends Controller
{
    public function __construct(private readonly RemiseService $remiseService)
    {
    }

    public function store(Request $request, int $studentId): JsonResponse
    {
        $validated = $request->validate([
            'amount_cents' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
            'granted_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        return response()->json($this->remiseService->addDiscount($studentId, $validated), 201);
    }

    public function index(int $studentId): JsonResponse
    {
        return response()->json($this->remiseService->getDiscountHistory($studentId));
    }
}
