<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Affiche la liste des tâches (RÉPARE L'ERREUR INDEX)
     */
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now()->startOfDay();

        // 1. Récupération selon le rôle
        if ($user->isAdmin()) {
            $collaborators = User::where('role_id', '!=', 1)->get();
            $tasks = Task::with('user')->latest()->get();
        } else {
            $collaborators = collect();
            $tasks = Task::where('user_id', $user->id)->latest()->get();
        }

        // 2. Injection de la logique de retard pour la vue
        $tasks->each(function ($task) use ($now) {
            $dueDate = Carbon::parse($task->due_date)->startOfDay();
            // Une tâche est en retard si elle est 'en cours' et que la date est passée
            $task->is_overdue = ($task->status === 'en cours' && $now->greaterThan($dueDate));
        });

        return view('tasks.index', compact('tasks', 'collaborators'));
    }

    /**
     * Enregistre une mission (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', 
            'due_date' => 'required|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id,
            'assigned_by' => Auth::id(),
            'status' => 'en cours',
        ]);

        return redirect()->back()->with('success', 'Mission assignée avec succès.');
    }

    /**
     * Validation avec gestion du temps de validité
     */
    public function confirm(Task $task)
    {
        $user = Auth::user();
        if ($user->id !== $task->user_id && !$user->isAdmin()) { abort(403); }

        $now = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($task->due_date)->startOfDay();

        // Si on clique APRÈS la date : ÉCHEC
        if ($now->greaterThan($dueDate)) {
            $task->update(['status' => 'échoué']);
            return redirect()->back()->with('error', 'Délai dépassé : mission marquée comme échouée (0 pts).');
        }

        // Si OK : TERMINÉ + POINTS
        $task->update(['status' => 'terminé']);

        $score = WeeklyScore::firstOrCreate([
            'user_id' => $task->user_id,
            'week_number' => now()->weekOfYear,
            'year' => now()->year,
        ]);

        $score->points_tasks = min(($score->points_tasks ?? 0) + 1.5, 5.0);
        $total = ($score->points_presence ?? 0) + ($score->points_tasks ?? 0);
        $score->score = min($total, 10);
        $score->save();

        return redirect()->back()->with('success', 'Mission validée ! Points ajoutés au score.');
    }
}