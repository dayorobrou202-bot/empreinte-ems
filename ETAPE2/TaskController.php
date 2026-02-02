<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index() {
        $user = auth()->user();

        // Si l'utilisateur est admin, afficher la liste des tâches pour l'admin
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $tasks = Task::with('user')->orderBy('created_at', 'desc')->get();
            $collaborators = User::all()->reject(function ($u) {
                return method_exists($u, 'isAdmin') && $u->isAdmin();
            });
            return view('admin.tasks.index', compact('tasks', 'collaborators'));
        }

        // Sinon, afficher les tâches de l'utilisateur connecté
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create(User $user) {
        return view('admin.tasks.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
            'user_id' => $request->input('user_id'),
            'assigned_by' => Auth::id(),
            'status' => 'en cours',
        ]);

        return redirect()->route('tasks.index')->with('success', 'La mission a été envoyée avec succès.');
    }

    /**
     * Permet au collaborateur de confirmer/valider une tâche qui lui est assignée.
     */
    public function confirm(Task $task)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        // Seul le collaborateur assigné (destinataire) peut confirmer
        if ($user->id !== $task->user_id) {
            abort(403);
        }

        // 'status' est un ENUM (voir migration). Utiliser une valeur valide : 'terminé'
        $task->status = 'terminé';
        $task->save();

        return redirect()->back()->with('success', 'Tâche marquée comme validée.');
    }
}
