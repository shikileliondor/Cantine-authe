<?php

use App\Http\Controllers\GestionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard.index')->name('dashboard');

Route::get('/gestion', [GestionController::class, 'index'])->name('gestion.index');

Route::redirect('/eleves', '/gestion')->name('eleves.index');
Route::redirect('/classes', '/gestion')->name('classes.index');

Route::view('/comptabilite', 'pages.placeholder', [
    'title' => 'Comptabilité',
    'headerTitle' => 'Comptabilité',
    'pageTitle' => 'Comptabilité',
])->name('comptabilite.index');

Route::view('/parametres', 'pages.placeholder', [
    'title' => 'Paramètres',
    'headerTitle' => 'Paramètres',
    'pageTitle' => 'Paramètres de l\'application',
])->name('settings.index');
