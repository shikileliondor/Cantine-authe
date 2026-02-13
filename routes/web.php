<?php

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
