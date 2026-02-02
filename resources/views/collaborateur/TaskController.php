<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Affiche la page des tâches.
     * Admin : voit le formulaire d'ajout et TOUTES les tâches.
     * Collab : voit uniquement ses tâches.
     */
    public function index()
    {
        $user = Auth::user();

        // Si c'est un Admin
        if ($user->isAdmin()) {
            // 1. On récupère les collaborateurs pour le menu déroulant
            $collaborators = User::where('id', '!=', $user->id)->get();
            
            // 2. On récupère TOUTES les tâches pour le suivi global
            $tasks = Task::with('user')->latest()->get();

            // 3. On renvoie vers la vue UNIQUE 'tasks.index'
            return view('tasks.index', compact('tasks', 'collaborators'));
        }

        // Si c'est un Collaborateur simple
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        
        // On renvoie vers la même vue, mais sans la liste des collaborateurs
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Enregistre la mission envoyée via le formulaire général.
     */
    public function store(Request $request)
    {
        // 1. Validation : on vérifie que user_id est bien présent (ID choisi dans le <select>)
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', 
            'due_date' => 'required|date',
        ]);

        // 2. Création de la tâche
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id, // Récupéré depuis le menu déroulant du formulaire
            'assigned_by' => Auth::id(),
            'status' => 'en cours',
        ]);

        return redirect()->route('tasks.index')->with('success', 'La mission a été envoyée avec succès.');
    }
}