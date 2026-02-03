<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\PresenceLog;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PresenceController extends Controller
{
    private $officeIp = '160.155.123.45'; // Ton IP de bureau

    /**
     * CETTE MÉTHODE MANQUAIT : Elle affiche la page de pointage
     */
    public function page()
    {
        $user = auth()->user();
        
        // Redirige l'admin vers sa vue s'il essaie d'accéder au pointage employé
        if ($user->role_id === 1) {
            return redirect()->route('admin.presences.index');
        }

        $now = now();
        $presence = Presence::where('user_id', $user->id)
            ->whereDate('date_pointage', $now->toDateString())
            ->first();

        // On retourne la vue du dashboard collaborateur
        return view('presences.index', compact('presence', 'now'));
    }

    /**
     * ACTION DE POINTAGE SÉCURISÉE
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $today = $now->toDateString();
        $userIp = $request->ip();

        // 1. Sécurité IP
        $type = $request->input('type', 'Bureau');
        if ($type !== 'Télétravail' && $userIp !== $this->officeIp && !app()->isLocal()) {
            return redirect()->back()->with('error', "IP non autorisée ($userIp).");
        }

        // 2. Récupération de la présence
        $presence = Presence::firstOrNew(['user_id' => $user->id, 'date_pointage' => $today]);
        if (Schema::hasColumn('presences', 'date')) { $presence->date = $today; }

        $points = 0;
        $moment = '';

        // 3. Logique Matin / Midi / Soir
        if (!$presence->heure_matin) {
            $presence->heure_matin = $now->format('H:i:s');
            $presence->type = $type;
            $presence->present = true;
            $moment = 'Matin';
            $points = 0.25;
            $message = "Matin validé.";
        } 
        elseif (!$presence->heure_midi) {
            if (Carbon::parse($presence->heure_matin)->diffInMinutes($now) < 90) {
                return redirect()->back()->with('error', "Attendez 1h30 entre deux pointages.");
            }
            $presence->heure_midi = $now->format('H:i:s');
            $moment = 'Midi';
            $points = 0.25;
            $message = "Midi validé.";
        } 
        elseif (!$presence->heure_soir) {
            if (Carbon::parse($presence->heure_midi)->diffInMinutes($now) < 90) {
                return redirect()->back()->with('error', "Attendez 1h30 après le midi.");
            }
            $presence->heure_soir = $now->format('H:i:s');
            $moment = 'Soir';
            
            // Calcul total
            $debut = Carbon::parse($presence->heure_matin);
            $heures = $debut->diffInMinutes($now) / 60;
            if ($heures > 5) { $heures -= 1; }
            $presence->total_heures = round($heures, 2);
            $points = ($heures >= 7) ? 0.75 : 0.25;
            $message = "Soir validé ($heures h).";
        } else {
            return redirect()->back()->with('error', "Journée terminée.");
        }

        $presence->save();
        $this->updateScore($user->id, $points);
        
        PresenceLog::create([
            'user_id' => $user->id, 
            'occurred_at' => $now, 
            'slot' => $moment, 
            'ip_address' => $userIp
        ]);

        return redirect()->back()->with('status', $message);
    }

    private function updateScore($userId, $pts)
    {
        $now = now();
        $score = WeeklyScore::firstOrCreate([
            'user_id' => $userId,
            'week_number' => $now->weekOfYear,
            'year' => $now->year,
        ]);
        $score->increment('points_presence', $pts);
        $score->score = min(($score->points_presence + ($score->points_tasks ?? 0)), 10);
        $score->save();
    }
}