<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceController extends Controller
{
    public function page(Request $request)
    {
        $user = auth()->user();
        $now = now();
        
        // On force l'accès pour tout le monde sans vérifier l'IP
        $isAtOffice = true; 
        $userIp = $request->ip();

        if ($user->isAdmin()) {
            $users = User::where('role_id', '!=', 1)->orderBy('name')->get();
            $filterUserId = $request->query('user_id');
            $filterDate = $request->query('date', $now->toDateString());

            $presenceRows = Presence::with('user')
                ->when($filterUserId, fn ($q) => $q->where('user_id', $filterUserId))
                ->whereDate('date', $filterDate)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('presences.index', compact('users', 'presenceRows', 'filterDate', 'now', 'isAtOffice', 'userIp'));
        }

        $presence = Presence::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        return view('presences.index', compact('presence', 'now', 'isAtOffice', 'userIp'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $now = now();

        $presence = Presence::firstOrNew([
            'user_id' => $user->id,
            'date' => $now->toDateString()
        ]);

        // 1. POINTAGE DU MATIN
        if (!$presence->heure_matin) {
            // On enregistre l'heure et le GPS sans poser de questions
            $presence->heure_matin = $now->format('H:i:s');
            $presence->type = 'Pointage';
            $presence->present = true;
            
            // On récupère le GPS s'il est là, sinon ça reste vide (pas de blocage)
            $presence->latitude_entree = $request->latitude;
            $presence->longitude_entree = $request->longitude;
            
            $presence->save();
            $this->addScore($user->id, 0.5);

            return back()->with('status', "Arrivée enregistrée à " . $now->format('H:i'));
        } 
        
        // 2. POINTAGE DU SOIR
        elseif (!$presence->heure_soir) {
            $presence->heure_soir = $now->format('H:i:s');
            
            // Calcul simple
            $heureArrivee = Carbon::parse($presence->heure_matin);
            $minutesTotales = $heureArrivee->diffInMinutes($now);
            $minutesTravaillees = max(0, $minutesTotales - 60); // On déduit 1h de pause
            
            $presence->total_heures = round($minutesTravaillees / 60, 2);

            $presence->latitude_sortie = $request->latitude;
            $presence->longitude_sortie = $request->longitude;

            $presence->save();
            $this->addScore($user->id, 0.5);

            return back()->with('status', "Sortie enregistrée. Total : " . $presence->total_heures . "h");
        } 

        return back()->with('error', "Journée déjà terminée.");
    }

    private function addScore($userId, $pts) {
        $score = WeeklyScore::firstOrCreate([
            'user_id' => $userId,
            'week_number' => now()->weekOfYear,
            'year' => now()->year,
        ]);
        $score->increment('points_presence', $pts);
    }
}