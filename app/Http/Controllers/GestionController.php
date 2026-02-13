<?php

namespace App\Http\Controllers;

use App\Services\ClasseService;
use App\Services\EleveService;
use Illuminate\View\View;

class GestionController extends Controller
{
    public function __construct(
        private readonly EleveService $eleveService,
        private readonly ClasseService $classeService,
    ) {
    }

    public function index(): View
    {
        $students = $this->eleveService->listStudents();
        $classes = $this->classeService->listClasses();

        return view('pages.gestion.index', [
            'students' => $students,
            'classes' => $classes,
        ]);
    }
}
