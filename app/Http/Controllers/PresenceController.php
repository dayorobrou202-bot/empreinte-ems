<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\PresenceLog;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresenceController extends Controller
{
    /**
     * Vue Admin : Historique Complet
     */
    public function index()
    {
        $today = now()->toDateString();
        $filterDate = request()->query('date', $today);
        
        $users = User::where('role_id', '!=', 1)->orderBy('name')->get();
        $presences = Presence::whereDate('date_pointage', $filterDate)->get()->keyBy('user_id');

        $presenceRows = collect();
        foreach ($users as $user) {
            $p = $presences->get($user->id);
            $presenceRows->push((object) [
                'user' => $user,
                'heure_matin' => $p->heure_matin ?? null,
                'heure_midi' => $p->heure_midi ?? null,
                'heure_soir' => $p->heure_soir ?? null,
                'total_heures' => $p->total_heures ?? 0,
                'present' => $p ? (bool) $p->present : false,
            ]);
        }

        return view('presences.admin_index', compact('users', 'today', 'filterDate', 'presenceRows'));
    }

    /**
     * Page de pointage (Employé)
     */
    public function page()
    {
        $user = auth()->user();

        if ($user->role_id === 1) {
            return redirect()->route('admin.presences.index');
        }

        $now = now();
        $today = $now->toDateString();
        
        $users = User::where('role_id', '!=', 1)->get();
        $presenceRows = collect(); 

        $presence = Presence::where('user_id', $user->id)
            ->whereDate('date_pointage', $today)
            ->first();

        $slots = [
            'matin' => ['start' => 6,  'end' => 12, 'label' => 'Matin'],
            'midi'  => ['start' => 12, 'end' => 15, 'label' => 'Midi'],
            'soir'  => ['start' => 15, 'end' => 21, 'label' => 'Soir'], 
        ];

        $slotData = [];
        foreach ($slots as $key => $config) {
            $field = "heure_" . $key;
            $pointed = $presence && !empty($presence->$field);
            $isCorrectTime = $now->hour >= $config['start'] && $now->hour < $config['end'];

            $slotData[$key] = [
                'label'   => $config['label'],
                'pointed' => $pointed,
                'active'  => $isCorrectTime && !$pointed,
                'time'    => $pointed ? $presence->$field : null
            ];
        }

        return view('presences.index', compact('presence', 'slotData', 'now', 'users', 'presenceRows'));
    }

    /**
     * Enregistrement du pointage (Nettoyé et Corrigé)
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role_id === 1) {
            return redirect()->back()->with('error', 'L\'administrateur ne peut pas pointer.');
        }

        $now = now();
        $today = $now->toDateString();

        // 1. Sécurité IP (Wi-Fi Bureau ou Local)
        if ($request->ip() !== '172.20.10.2' && $request->ip() !== '127.0.0.1') {
             return redirect()->back()->with('error', 'Accès refusé : Connectez-vous au Wi-Fi du bureau.');
        }

        // 2. Récupération ou création de la présence du jour
        $presence = Presence::firstOrNew([
            'user_id' => $user->id, 
            'date_pointage' => $today
        ]);

        $h = $now->hour;
        $moment = '';
        $pointsAujourdhui = 0;

        // 3. Logique des créneaux et attribution des points
        if ($h >= 6 && $h < 12) { 
            if ($presence->heure_matin) return redirect()->back()->with('error', 'Matin déjà pointé.');
            $presence->heure_matin = $now->format('H:i:s');
            $presence->present = true;
            $moment = 'matin';
            $pointsAujourdhui = 0.25; 
        } 
        elseif ($h >= 12 && $h < 15) { 
            if ($presence->heure_midi) return redirect()->back()->with('error', 'Midi déjà pointé.');
            $presence->heure_midi = $now->format('H:i:s');
            $moment = 'midi';
            $pointsAujourdhui = 0.15;
        } 
        elseif ($h >= 15 && $h < 21) { 
            if ($presence->heure_soir) return redirect()->back()->with('error', 'Soir déjà pointé.');
            $presence->heure_soir = $now->format('H:i:s');
            $moment = 'soir';
            $pointsAujourdhui = 0.20;

            // Calcul du bonus si journée complète
            if ($presence->heure_matin) {
                $debut = Carbon::parse($presence->heure_matin);
                $minutes = $debut->diffInMinutes($now);
                if ($presence->heure_midi) { $minutes -= 60; }
                $presence->total_heures = max(0, round($minutes / 60, 2));

                if ($presence->total_heures >= 7) {
                    $pointsAujourdhui += 0.40; // Bonus assiduité
                }
            }
        } else {
            return redirect()->back()->with('error', 'Aucun créneau ouvert actuellement.');
        }

        // Assurer que le champ `date` (non-nullable en base) est renseigné
        if (Schema::hasColumn('presences', 'date')) {
            $presence->date = $today;
        }

        $presence->save();

        // 4. Mise à jour du Score Hebdomadaire (Moteur du classement)
        $weeklyScore = WeeklyScore::firstOrCreate([
            'user_id' => $user->id,
            'week_number' => $now->weekOfYear,
            'year' => $now->year,
        ]);

        // On ajoute les points gagnés au cumul
        $weeklyScore->increment('points_presence', $pointsAujourdhui);
        
        // Calcul du score total (max 10 points)
        $total = $weeklyScore->points_presence + ($weeklyScore->points_tasks ?? 0) + ($weeklyScore->points_collaboration ?? 0);
        $weeklyScore->score = min($total, 10);
        $weeklyScore->save();

        // 5. Historique (Logs)
        PresenceLog::create([
            'user_id' => $user->id, 
            'occurred_at' => $now, 
            'slot' => $moment
        ]);

        return redirect()->back()->with('status', "Pointage du $moment validé (+ $pointsAujourdhui pts) !");
    }
}