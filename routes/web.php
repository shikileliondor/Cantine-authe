<?php

use App\Http\Controllers\GestionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard.index')->name('dashboard');

Route::get('/gestion', [GestionController::class, 'index'])->name('gestion.index');

Route::get('/comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite.index');
Route::post('/comptabilite/eleves/{studentId}/versements', [ComptabiliteController::class, 'storePayment'])->name('comptabilite.payments.store');
Route::post('/comptabilite/eleves/{studentId}/remises', [ComptabiliteController::class, 'storeDiscount'])->name('comptabilite.discounts.store');

Route::view('/parametres', 'pages.placeholder', [
    'title' => 'Paramètres',
    'headerTitle' => 'Paramètres',
    'pageTitle' => 'Paramètres de l\'application',
])->name('settings.index');
