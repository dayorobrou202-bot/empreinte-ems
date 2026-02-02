<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\WeeklyScore; // Importation du modèle de points
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompleted;

class TaskController extends Controller
{
    /**
     * Liste des tâches (Vue Admin ou Collaborateur)
     */
    public function index()
    {
        $user = auth()->user();

        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $tasks = Task::with('user')->orderBy('created_at', 'desc')->get();
            $collaborators = User::all()->reject(function ($u) {
                return method_exists($u, 'isAdmin') && $u->isAdmin();
            });
            return view('admin.tasks.index', compact('tasks', 'collaborators'));
        }

        $tasks = Task::where('user_id', $user->id)->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Formulaire de création (Admin uniquement)
     */
    public function create(User $user)
    {
        return view('admin.tasks.create', compact('user'));
    }

    /**
     * Enregistre une nouvelle mission
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
            'user_id' => $request->input('user_id'),
            'assigned_by' => Auth::id(),
            'status' => 'en cours',
        ]);

        // Notifier le collaborateur
        $assignee = User::find($request->input('user_id'));
        if ($assignee) {
            $assignee->notify(new TaskAssigned($task));
        }

        return redirect()->route('tasks.index')->with('success', 'La mission a été envoyée avec succès.');
    }

    /**
     * Valide une mission et calcule les points d'efficacité
     */
    public function confirm(Task $task)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // Vérification des droits : seul l'assigné ou l'admin peut valider
        if ($user->id !== $task->user_id && (!method_exists($user, 'isAdmin') || !$user->isAdmin())) {
            abort(403);
        }

        // 1. Mise à jour du statut de la tâche
        $task->status = 'terminé';
        $task->save();

        // 2. LOGIQUE DE POINTS ÉQUILIBRÉE
        // On récupère ou crée le score de la semaine actuelle pour cet utilisateur
        $score = WeeklyScore::firstOrCreate([
            'user_id' => $task->user_id,
            'week_number' => now()->weekOfYear,
            'year' => now()->year,
        ]);

        // On ajoute 1.5 points par tâche, mais on bloque à 5.0 points MAX pour cette catégorie
        $score->points_tasks = min($score->points_tasks + 1.5, 5.0);
        
        // Calcul du score global sur 10 (Somme de toutes les catégories)
        $totalGeneral = $score->points_presence + $score->points_tasks + $score->points_collaboration + $score->points_manual;
        
        // On s'assure que le score final ne dépasse jamais 10/10
        $score->score = min($totalGeneral, 10);
        $score->save();

        // 3. Notification pour l'admin (Ton code original)
        if (!empty($task->assigned_by)) {
            $creator = User::find($task->assigned_by);
            if ($creator) {
                $creator->notify(new TaskCompleted($task));
            }
        }

        return redirect()->back()->with('success', 'Mission validée ! Votre efficacité a été mise à jour.');
    }
}