<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conge;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CongeStatusChanged;

class CongeController extends Controller
{
    /**
     * Affiche la liste des congés (Admin ou Collaborateur)
     */
    public function index()
    {
        $user = Auth::user();

        // Si l'utilisateur est admin
        if ($user->role_id == 1 || $user->isAdmin()) {
            $conges = Conge::with('user')->orderBy('created_at', 'desc')->get();
            return view('pages.admin.conges_equipe', compact('conges'));
        }

        // Si c'est un collaborateur
        $conges = Conge::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('pages.conges', compact('conges'));
    }

    /**
     * Enregistre une nouvelle demande de congé
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'type'       => 'required|string',
        ]);

        Conge::create([
            'user_id'    => Auth::id(),
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'type'       => $request->type,
            'reason'     => $request->reason,
            'status'     => 'en_attente',
        ]);

        return redirect()->back()->with('success', 'Demande envoyée !');
    }

    /**
     * Page d'administration listant toutes les demandes
     */
    public function adminIndex()
    {
        $conges = Conge::with('user')->orderBy('created_at', 'desc')->get();
        return view('pages.admin.conges_equipe', compact('conges'));
    }

    /**
     * Mise à jour générique du statut
     */
    public function update(Request $request, $id)
    {
        $conge = Conge::findOrFail($id);
        $conge->status = $request->input('status');
        $conge->save();

        return redirect()->back()->with('success', 'Statut mis à jour !');
    }

    /**
     * Approuver un congé (Admin)
     */
    public function approve($id)
    {
        $conge = Conge::findOrFail($id);

        if (strtolower($conge->status) !== 'en_attente') {
            return redirect()->back()->with('warning', 'Cette demande a déjà été traitée.');
        }

        $conge->status = 'approuve';
        $conge->save();

        try {
            $adminName = Auth::user() ? Auth::user()->name : null;
            $conge->user->notify(new CongeStatusChanged($conge, 'approuve', $adminName));
        } catch (\Exception $e) {
            // Silence
        }

        return redirect()->back()->with('success', 'Congé approuvé.');
    }

    /**
     * Rejeter un congé (Admin)
     */
    public function reject($id)
    {
        $conge = Conge::findOrFail($id);

        if (strtolower($conge->status) !== 'en_attente') {
            return redirect()->back()->with('warning', 'Cette demande a déjà été traitée.');
        }

        $conge->status = 'refuse';
        $conge->save();

        try {
            $adminName = Auth::user() ? Auth::user()->name : null;
            $conge->user->notify(new CongeStatusChanged($conge, 'refuse', $adminName));
        } catch (\Exception $e) {
            // Silence
        }

        return redirect()->back()->with('success', 'Congé refusé.');
    }

    /**
     * Ancienne méthode de mise à jour simple
     */
    public function updateStatus(Request $request, $id)
    {
        $conge = Conge::findOrFail($id);
        $conge->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Statut mis à jour !');
    }
}