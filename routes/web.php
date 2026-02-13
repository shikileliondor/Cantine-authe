<?php

use App\Http\Controllers\ComptabiliteController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard.index')->name('dashboard');

Route::get('/gestion', [GestionController::class, 'index'])->name('gestion.index');

Route::get('/comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite.index');
Route::post('/comptabilite/eleves/{studentId}/versements', [ComptabiliteController::class, 'storePayment'])->name('comptabilite.payments.store');
Route::post('/comptabilite/eleves/{studentId}/remises', [ComptabiliteController::class, 'storeDiscount'])->name('comptabilite.discounts.store');

Route::get('/parametres', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/parametres/annees', [SettingsController::class, 'storeYear'])->name('settings.years.store');
Route::post('/parametres/annees/{yearId}/activer', [SettingsController::class, 'activateYear'])->name('settings.years.activate');
Route::post('/parametres/annees/{yearId}/archiver', [SettingsController::class, 'archiveYear'])->name('settings.years.archive');
