<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Presence;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $today = $now->toDateString();

        // 1. Statistiques des collaborateurs et tâches
        $collaborateursCount = User::where('role_id', '!=', 1)->count();
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'en cours')->count();

        // 2. COMPTAGE DES PRÉSENCES (On utilise la colonne 'date')
        $presenceCount = Presence::whereDate('date', $today)->count();
        
        // Pour les boutons de pointage de l'utilisateur connecté
        $todayPresence = Presence::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        // 3. CLASSEMENT DES PERFORMANCES (Correction : points_presence au lieu de score)
        $topUsers = User::where('role_id', '!=', 1)
            ->leftJoin('weekly_scores', function($join) use ($now) {
                $join->on('users.id', '=', 'weekly_scores.user_id')
                     ->where('weekly_scores.week_number', '=', $now->weekOfYear)
                     ->where('weekly_scores.year', '=', $now->year);
            })
            ->select([
                'users.id',
                'users.name',
                // On utilise COALESCE pour afficher 0 si aucun point n'est encore enregistré
                DB::raw('COALESCE(weekly_scores.points_presence, 0) as current_score')
            ])
            ->orderByDesc('current_score') 
            ->orderBy('users.name')
            ->get();

        // Conversion du score pour la vue (ex: 1 point par jour -> 10% par jour)
        $topUsers->transform(function ($u) {
            $u->avgScore = (int) ($u->current_score * 10); 
            return $u;
        });

        // Moyenne générale de performance de l'équipe
        $avgScore = $topUsers->count() > 0 ? (int) $topUsers->avg('avgScore') : 0;

        return view('dashboard', compact(
            'collaborateursCount',
            'totalTasks',
            'pendingTasks',
            'avgScore',
            'presenceCount',
            'todayPresence',
            'topUsers'
        ));
    }
}