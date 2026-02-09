<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\PresenceLog;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceController extends Controller
{
    private $allowedIps = ['102.67.252.62', '160.155.123.45', '41.202.219.8'];

    // J'ai renommé en index pour correspondre aux routes standards de Laravel
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');
        
        $now = now();
        $userIp = $request->header('X-Forwarded-For') ?? $request->ip();

        // Remplace $user->isAdmin() qui causait l'erreur 500
        if ($user->role_id == 1) {
            $users = User::where('role_id', '!=', 1)->orderBy('name')->get();
            $filterUserId = $request->query('user_id');
            $filterDate = $request->query('date', $now->toDateString());

            $presenceRows = Presence::with('user')
                ->when($filterUserId, fn ($q) => $q->where('user_id', $filterUserId))
                ->whereDate('date', $filterDate)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('presences.index', compact('users', 'presenceRows', 'filterDate', 'now'));
        }

        // Vue collaborateur
        $presence = Presence::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        $isAtOffice = false;
        foreach($this->allowedIps as $ip) {
            if(str_contains($userIp, $ip)) $isAtOffice = true;
        }

        return view('presences.index', compact('presence', 'now', 'isAtOffice', 'userIp'));
    }

    // Garde 'page' au cas où tes routes l'utilisent encore
    public function page(Request $request) {
        return $this->index($request);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $userIp = $request->header('X-Forwarded-For') ?? $request->ip();
        
        $isAuthorized = false;
        foreach($this->allowedIps as $ip) {
            if(str_contains($userIp, $ip)) $isAuthorized = true;
        }

        // Autorise en local OU si l'IP est reconnue
        if (!app()->isLocal() && !$isAuthorized) {
            return back()->with('error', "Accès refusé. IP $userIp non reconnue.");
        }

        // Utilisation de 'date' comme dans ta base de données
        $presence = Presence::firstOrNew([
            'user_id' => $user->id,
            'date' => $now->toDateString()
        ]);

        $points = 0;
        if (!$presence->heure_matin) {
            $presence->heure_matin = $now->format('H:i:s');
            $presence->type = 'Bureau';
            $presence->present = true;
            $points = 0.5;
            $msg = "Arrivée enregistrée.";
        } elseif (!$presence->heure_soir) {
            $presence->heure_soir = $now->format('H:i:s');
            $diff = Carbon::parse($presence->heure_matin)->diffInMinutes($now);
            $heures = $diff / 60;
            if ($heures > 5) $heures -= 1; 
            $presence->total_heures = round($heures, 2);
            $points = 0.5;
            $msg = "Sortie enregistrée.";
        } else {
            return back()->with('error', "Déjà pointé aujourd'hui.");
        }

        $presence->save();
        $this->addScore($user->id, $points);

        return back()->with('status', $msg);
    }

    private function addScore($userId, $pts) {
        $score = WeeklyScore::firstOrCreate([
            'user_id' => $userId,
            'week_number' => now()->weekOfYear,
            'year' => now()->year,
        ]);
        $score->increment('points_presence', $pts);
        
        // Recalcul du total avec les missions du TaskController
        $total = ($score->points_presence ?? 0) + ($score->points_tasks ?? 0);
        $score->score = min($total, 10);
        $score->save();
    }
}