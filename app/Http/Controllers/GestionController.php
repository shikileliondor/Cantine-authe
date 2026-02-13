<?php

namespace App\Http\Controllers;

use App\Services\EleveService;
use App\Services\ClasseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GestionController extends Controller
{
    public function __construct(
        private readonly EleveService $eleveService,
        private readonly ClasseService $classeService
    )
    {
    }

    public function index(Request $request): View
    {
        $yearId = $request->integer('school_year_id') ?: null;
        $students = $this->eleveService->listStudents($yearId, true);
        $classes = $this->classeService->listClasses($yearId);

        return view('gestion.index', [
            'students' => $students,
            'classes' => $classes,
            'classesCount' => $classes->count(),
            'studentsCount' => $students->count(),
        ]);
    }

    public function classes(Request $request): View
    {
        return view('gestion.classes', [
            'classes' => $this->classeService->listClasses($request->integer('school_year_id') ?: null),
        ]);
    }

    public function students(Request $request): View
    {
        return view('gestion.students', [
            'students' => $this->eleveService->listStudents($request->integer('school_year_id') ?: null, true),
            'classes' => $this->classeService->listClasses($request->integer('school_year_id') ?: null),
        ]);
    }

    public function showStudent(int $studentId): View
    {
        $profile = $this->eleveService->getStudentProfile($studentId);

        return view('gestion.students-show', $profile);
    }
}
