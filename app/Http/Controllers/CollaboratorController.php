<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaboratorController extends Controller
{
    // Affiche la liste des utilisateurs pour l'Admin
    public function list()
    {
        // On récupère les utilisateurs avec leur mentor pour éviter les requêtes en boucle
        $users = User::with('mentor')->paginate(10);
        return view('admin.users_list', ['collaborators' => $users]);
    }

    /**
     * Affiche le profil détaillé d'un collaborateur ou stagiaire
     */
    public function show(User $user)
    {
        // On charge explicitement le mentor et les performances pour éviter l'erreur "sortByDesc on null"
        $user->load(['mentor', 'performances']);
        
        $tasks = $user->tasks()->latest()->get();
        
        return view('collaborateur.show', compact('user', 'tasks'));
    }

    /**
     * AFFICHE MON PROPRE PROFIL
     */
    public function showSelf()
    {
        $user = Auth::user();
        
        // On charge les relations pour l'utilisateur connecté aussi
        $user->load(['mentor', 'performances']);
        
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        
        return view('collaborateur.show', compact('user', 'tasks'));
    }

    /**
     * Enregistre une évaluation (Performance)
     */
    public function evaluate(Request $request, User $user)
    {
        $data = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'comment' => 'nullable|string|max:1000',
        ]);

        Performance::create([
            'user_id' => $user->id,
            'score' => $data['score'],
            'comment' => $data['comment'] ?? null,
            'evaluated_by' => Auth::id(),
        ]);

        // Correction de la route pour correspondre à tes noms de routes habituels
        return redirect()->back()->with('success', 'Évaluation enregistrée.');
    }
}