<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PreferencesController extends Controller
{
    /**
     * Afficher la page de préférences
     */
    public function show(): View
    {
        return view('preferences.index', [
            'user' => auth()->user(),
            'themes' => ['light' => 'Clair', 'dark' => 'Sombre'],
            'colors' => [
                'blue' => 'Bleu',
                'red' => 'Rouge',
                'green' => 'Vert',
                'purple' => 'Violet',
                'indigo' => 'Indigo',
                'pink' => 'Rose',
            ],
            'positions' => ['left' => 'Gauche', 'right' => 'Droite'],
        ]);
    }

    /**
     * Mettre à jour les préférences
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'color_primary' => 'required|in:blue,red,green,purple,indigo,pink',
            'sidebar_position' => 'required|in:left,right',
            'show_statistics' => 'boolean',
            'show_performance_chart' => 'boolean',
            'items_per_page' => 'required|integer|min:5|max:100',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('preferences.show')
            ->with('success', 'Vos préférences ont été mises à jour avec succès !');
    }
}
