<?php

namespace App\Http\Controllers;

use App\Services\YearService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function __construct(private readonly YearService $yearService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->yearService->getAllYears());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        return response()->json($this->yearService->createYear($validated), 201);
    }

    public function setActive(int $yearId): JsonResponse
    {
        return response()->json($this->yearService->setActiveYear($yearId));
    }

    public function archive(int $yearId): JsonResponse
    {
        return response()->json($this->yearService->archiveYear($yearId));
    }

    public function archived(): JsonResponse
    {
        return response()->json($this->yearService->getArchivedYears());
    }
}
