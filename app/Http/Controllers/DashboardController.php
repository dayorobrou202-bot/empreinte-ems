<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Presence;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $today = $now->toDateString();

        // 1. Statistiques
        $collaborateursCount = User::where('role_id', '!=', 1)->count();
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'en cours')->count();

        // 2. Présences
        $presenceCount = Presence::whereDate('date_pointage', $today)->count();
        $todayPresence = Presence::where('user_id', auth()->id())
            ->whereDate('date_pointage', $today)
            ->first();

        // 3. CLASSEMENT COMPLET (Sans limite et sans erreur de colonne)
        $topUsers = User::where('role_id', '!=', 1)
            ->leftJoin('weekly_scores', function($join) use ($now) {
                $join->on('users.id', '=', 'weekly_scores.user_id')
                     ->where('weekly_scores.week_number', '=', $now->weekOfYear)
                     ->where('weekly_scores.year', '=', $now->year);
            })
            ->select([
                'users.id',
                'users.name',
                // 'users.position', // RETIRÉ : Cause de l'erreur SQL
                DB::raw('COALESCE(weekly_scores.score, 0) as current_score')
            ])
            ->orderByDesc('current_score') 
            ->orderBy('users.name')
            // ->limit(5) // RETIRÉ : Pour afficher toute l'équipe
            ->get();

        // Transformer le score en pourcentage
        $topUsers->transform(function ($u) {
            $u->avgScore = (int) ($u->current_score * 10); 
            return $u;
        });

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