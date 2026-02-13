<?php

namespace App\Http\Controllers;

use App\Services\ClasseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function __construct(private readonly ClasseService $classeService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->classeService->listClasses($request->integer('school_year_id') ?: null)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_year_id' => ['sometimes', 'integer', 'exists:school_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'canteen_amount_cents' => ['required', 'integer', 'min:0'],
        ]);

        return response()->json($this->classeService->createClass($validated), 201);
    }

    public function show(int $classId): JsonResponse
    {
        return response()->json($this->classeService->getClass($classId));
    }

    public function update(Request $request, int $classId): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'canteen_amount_cents' => ['sometimes', 'integer', 'min:0'],
        ]);

        return response()->json($this->classeService->updateClass($classId, $validated));
    }

    public function destroy(int $classId): JsonResponse
    {
        $this->classeService->deleteClass($classId);

        return response()->json([], 204);
    }
}
