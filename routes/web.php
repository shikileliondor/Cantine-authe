<?php

use App\Http\Controllers\ComptabiliteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard.index')->name('dashboard');

Route::view('/eleves', 'pages.placeholder', [
    'title' => 'Élèves',
    'headerTitle' => 'Élèves',
    'pageTitle' => 'Gestion des élèves',
])->name('eleves.index');

Route::view('/classes', 'pages.placeholder', [
    'title' => 'Classes',
    'headerTitle' => 'Classes',
    'pageTitle' => 'Gestion des classes',
])->name('classes.index');

Route::get('/comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite.index');
Route::post('/comptabilite/eleves/{studentId}/versements', [ComptabiliteController::class, 'storePayment'])->name('comptabilite.payments.store');
Route::post('/comptabilite/eleves/{studentId}/remises', [ComptabiliteController::class, 'storeDiscount'])->name('comptabilite.discounts.store');

Route::view('/parametres', 'pages.placeholder', [
    'title' => 'Paramètres',
    'headerTitle' => 'Paramètres',
    'pageTitle' => 'Paramètres de l\'application',
])->name('settings.index');
