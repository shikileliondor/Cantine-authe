<?php

namespace App\Http\Controllers;

use App\Services\YearService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View as ViewResponse;
use RuntimeException;

class SettingsController extends Controller
{
    public function __construct(private readonly YearService $yearService)
    {
    }

    public function index(): ViewResponse
    {
        return view('pages.settings.index', [
            'title' => 'Paramètres',
            'headerTitle' => 'Paramètres',
            'years' => $this->yearService->getAllYears(),
            'activeYear' => $this->yearService->getActiveYearOrNull(),
        ]);
    }

    public function storeYear(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $this->yearService->createYear($validated);

        return redirect()
            ->route('settings.index')
            ->with('status', 'Année scolaire créée avec succès.');
    }

    public function activateYear(int $yearId): RedirectResponse
    {
        $this->yearService->setActiveYear($yearId);

        return redirect()
            ->route('settings.index')
            ->with('status', 'Année scolaire active mise à jour.');
    }

    public function archiveYear(int $yearId): RedirectResponse
    {
        try {
            $this->yearService->archiveYear($yearId);

            return redirect()
                ->route('settings.index')
                ->with('status', 'Année scolaire archivée avec succès.');
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('settings.index')
                ->with('error', $exception->getMessage());
        }
    }
}
