<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\WeeklyScore;
use App\Notifications\TaskCompleted;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Affiche la liste des missions (Vue Admin ou Collaborateur)
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $now = Carbon::now()->startOfDay();

        // --- 1. LOGIQUE ADMIN ---
        if ($user->role_id == 1) { 
            $tasks = Task::with('user')->orderBy('created_at', 'desc')->get();
            $collaborators = User::where('role_id', '!=', 1)->get();

            $tasks->each(function($task) use ($now) {
                $dueDate = Carbon::parse($task->due_date)->startOfDay();
                $task->is_overdue = ($task->status === 'en cours' && $now->greaterThan($dueDate));
            });

            return view('admin.tasks.index', compact('tasks', 'collaborators'));
        }

        // --- 2. LOGIQUE COLLABORATEUR ---
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        
        $tasks->each(function($task) use ($now) {
            $dueDate = Carbon::parse($task->due_date)->startOfDay();
            $task->is_overdue = ($task->status === 'en cours' && $now->greaterThan($dueDate));
        });

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Enregistre une nouvelle mission (Action Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        Task::create([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'due_date'    => $request->input('due_date'),
            'user_id'     => $request->input('user_id'),
            'assigned_by' => Auth::id(),
            'status'      => 'en cours',
        ]);

        return redirect()->back()->with('success', 'La mission a été envoyée avec succès.');
    }

    /**
     * Valide ou marque en échec une mission (Action Collaborateur)
     */
    public function confirm(Task $task)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        // Vérification des droits : propriétaire de la tâche ou admin
        if ($user->id !== $task->user_id && $user->role_id != 1) {
            abort(403);
        }

        $now = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($task->due_date)->startOfDay();

        // CAS A : ÉCHEC SI RETARD
        if ($task->status === 'en cours' && $now->greaterThan($dueDate)) {
            $task->update(['status' => 'échoué']);
            return redirect()->back()->with('error', 'Délai dépassé ! Mission marquée comme échouée.');
        }

        // CAS B : SUCCÈS (Dans les temps)
        $task->update(['status' => 'terminé']);

        // --- MISE À JOUR DU SCORE HEBDOMADAIRE ---
        $score = WeeklyScore::firstOrCreate([
            'user_id'     => $task->user_id,
            'week_number' => now()->weekOfYear,
            'year'        => now()->year,
        ]);

        // Ajout des points (max 5 points pour la catégorie tâches)
        $score->points_tasks = min(($score->points_tasks ?? 0) + 1.5, 5.0);
        
        // Calcul du total général sur 10
        $totalGeneral = ($score->points_presence ?? 0) + 
                        ($score->points_tasks ?? 0) + 
                        ($score->points_collaboration ?? 0) + 
                        ($score->points_manual ?? 0);
        
        $score->score = min($totalGeneral, 10);
        $score->save();

        // --- NOTIFICATION À L'ADMIN ---
        if (!empty($task->assigned_by)) {
            $creator = User::find($task->assigned_by);
            if ($creator) {
                try { 
                    $creator->notify(new TaskCompleted($task)); 
                } catch (\Exception $e) {
                    // On ne bloque pas le processus si le serveur mail est déconnecté
                }
            }
        }

        return redirect()->back()->with('success', 'Mission validée ! Score mis à jour.');
    }
}