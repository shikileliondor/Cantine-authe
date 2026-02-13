<?php

use App\Http\Controllers\ComptabiliteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

$pinStoragePath = storage_path('app/cantine-pin.txt');

$getConfiguredPin = static function () use ($pinStoragePath): string {
    if (File::exists($pinStoragePath)) {
        return trim((string) File::get($pinStoragePath));
    }

    return (string) config('app.cantine_pin', '1234');
};

Route::get('/auth/pin', function () {
    return view('auth.pin-login');
})->name('pin.login');

Route::post('/auth/pin', function (Request $request) use ($getConfiguredPin) {
    $validated = $request->validate([
        'pin' => ['required', 'digits:4'],
    ]);

    if ($validated['pin'] !== $getConfiguredPin()) {
        return back()
            ->withErrors(['pin' => 'PIN incorrect. Vérifiez votre code puis réessayez.'])
            ->withInput();
    }

    $request->session()->put('pin_authenticated', true);

    return redirect()->route('dashboard');
})->name('pin.authenticate');

Route::get('/auth/pin/setup', function () {
    return view('auth.pin-setup');
})->name('pin.setup');

Route::post('/auth/pin/setup', function (Request $request) use ($pinStoragePath) {
    $validated = $request->validate([
        'admin_code' => ['required', 'string'],
        'new_pin' => ['required', 'digits:4', 'confirmed'],
    ], [
        'new_pin.confirmed' => 'La confirmation du PIN ne correspond pas.',
    ]);

    $expectedAdminCode = (string) config('app.cantine_pin_admin_code', 'ADMIN1234');

    if ($validated['admin_code'] !== $expectedAdminCode) {
        return back()
            ->withErrors(['admin_code' => 'Code admin invalide.'])
            ->withInput();
    }

    File::ensureDirectoryExists(dirname($pinStoragePath));
    File::put($pinStoragePath, $validated['new_pin']);

    $request->session()->forget('pin_authenticated');

    return redirect()
        ->route('pin.login')
        ->with('status', 'PIN créé avec succès. Vous pouvez maintenant vous connecter.');
})->name('pin.setup.store');

Route::get('/', function () {
    if (!session('pin_authenticated')) {
        return redirect()->route('pin.login');
    }

    return app(DashboardController::class)->index();
})->name('dashboard');

Route::get('/gestion', [GestionController::class, 'index'])->name('gestion.index');
Route::get('/gestion/classes', [GestionController::class, 'classes'])->name('gestion.classes');
Route::get('/gestion/eleves', [GestionController::class, 'students'])->name('gestion.students');
Route::get('/gestion/eleves/{studentId}', [GestionController::class, 'showStudent'])->name('gestion.students.show');

Route::prefix('/api')->group(function (): void {
    Route::apiResource('classes', ClasseController::class)->parameters(['classes' => 'classId']);
    Route::apiResource('eleves', EleveController::class)->parameters(['eleves' => 'studentId']);
    Route::post('eleves/{studentId}/restore', [EleveController::class, 'restore'])->name('eleves.restore');
});

Route::get('/comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite.index');
Route::post('/comptabilite/eleves/{studentId}/versements', [ComptabiliteController::class, 'storePayment'])->name('comptabilite.payments.store');
Route::post('/comptabilite/eleves/{studentId}/remises', [ComptabiliteController::class, 'storeDiscount'])->name('comptabilite.discounts.store');

Route::get('/parametres', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/parametres/annees', [SettingsController::class, 'storeYear'])->name('settings.years.store');
Route::post('/parametres/annees/{yearId}/activer', [SettingsController::class, 'activateYear'])->name('settings.years.activate');
Route::post('/parametres/annees/{yearId}/archiver', [SettingsController::class, 'archiveYear'])->name('settings.years.archive');
