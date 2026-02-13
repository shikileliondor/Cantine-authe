<?php

namespace App\Http\Controllers;

use App\Services\EleveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    public function __construct(private readonly EleveService $eleveService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->eleveService->listStudents($request->integer('school_year_id') ?: null)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:30'],
        ]);

        return response()->json($this->eleveService->createStudent($validated), 201);
    }

    public function show(int $studentId): JsonResponse
    {
        return response()->json($this->eleveService->getStudentProfile($studentId));
    }

    public function update(Request $request, int $studentId): JsonResponse
    {
        $validated = $request->validate([
            'school_class_id' => ['sometimes', 'integer', 'exists:school_classes,id'],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'guardian_name' => ['sometimes', 'string', 'max:255'],
            'guardian_phone' => ['sometimes', 'string', 'max:30'],
        ]);

        return response()->json($this->eleveService->updateStudent($studentId, $validated));
    }

    public function destroy(int $studentId): JsonResponse
    {
        $this->eleveService->deleteStudent($studentId);

        return response()->json([], 204);
    }

    public function restore(int $studentId): JsonResponse
    {
        return response()->json($this->eleveService->restoreStudent($studentId));
    }
}
