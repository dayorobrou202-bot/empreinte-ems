<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\WeeklyScore;
use App\Notifications\TaskCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $now = Carbon::now()->startOfDay();
        
        // On initialise toujours pour éviter l'erreur "Variable undefined" dans la vue
        $collaborators = User::where('role_id', '!=', 1)->orderBy('name')->get();

        if ($user->role_id == 1) {
            // Logique ADMIN
            $tasks = Task::with('user')->orderBy('created_at', 'desc')->get();
            
            $tasks->each(function($t) use ($now) {
                $dueDate = Carbon::parse($t->due_date)->startOfDay();
                $t->is_overdue = ($t->status === 'en cours' && $now->gt($dueDate));
            });

            // VÉRIFIE BIEN QUE CE DOSSIER EXISTE SUR TON SERVEUR
            return view('admin.tasks.index', compact('tasks', 'collaborators'));
        }

        // Logique COLLABORATEUR
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        
        $tasks->each(function($t) use ($now) {
            $dueDate = Carbon::parse($t->due_date)->startOfDay();
            $t->is_overdue = ($t->status === 'en cours' && $now->gt($dueDate));
        });

        return view('tasks.index', compact('tasks', 'collaborators'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date'
        ]);

        Task::create($data + [
            'assigned_by' => Auth::id(), 
            'status' => 'en cours'
        ]);

        return back()->with('success', 'Mission assignée avec succès.');
    }

    public function confirm($id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Sécurité
        if ($user->id !== $task->user_id && $user->role_id != 1) {
            return back()->with('error', 'Action non autorisée.');
        }

        // Mise à jour de la tâche
        $task->update([
            'status' => 'terminé', 
            'completed_at' => now()
        ]);

        // Mise à jour du score hebdomadaire
        $score = WeeklyScore::firstOrCreate([
            'user_id' => $task->user_id,
            'week_number' => now()->weekOfYear,
            'year' => now()->year,
        ]);

        // Utilisation de increment pour la sécurité SQL
        $score->increment('points_tasks', 1.5);
        
        // Recalcul du score final (plafonné à 10)
        $total = (float)($score->points_presence ?? 0) + (float)($score->points_tasks ?? 0);
        $score->score = min($total, 10);
        $score->save();

        // Notification
        if ($task->assigned_by) {
            $admin = User::find($task->assigned_by);
            if ($admin) {
                try {
                    $admin->notify(new TaskCompleted($task));
                } catch (\Exception $e) {
                    // On ignore si le serveur mail n'est pas prêt
                }
            }
        }

        return back()->with('success', 'Mission terminée et points ajoutés !');
    }

    public function destroy(Task $task)
    {
        if (Auth::user()->role_id != 1) {
            return back()->with('error', 'Action réservée aux administrateurs.');
        }
        
        $task->delete();
        return back()->with('success', 'Mission supprimée.');
    }
}