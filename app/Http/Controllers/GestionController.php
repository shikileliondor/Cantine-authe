<?php

namespace App\Http\Controllers;

use App\Services\EleveService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GestionController extends Controller
{
    public function __construct(private readonly EleveService $eleveService)
    {
    }

    public function index(Request $request): View
    {
        $students = $this->eleveService->listStudents($request->integer('school_year_id') ?: null);

        return view('gestion.index', [
            'students' => $students,
        ]);
    }
}
