<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceController extends Controller
{
    // IPs autorisées du bureau
    private $allowedIps = ['102.67.252.62', '160.155.123.45', '41.202.219.8'];

    public function page(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $userIp = $this->getRealIp($request);

        // Autorisation : Admin ou IP reconnue
        $isAtOffice = in_array($userIp, $this->allowedIps) || $user->role_id == 1;

        if (!$isAtOffice) {
            session()->now('warning', "IP non reconnue ($userIp). Veuillez utiliser le Wi-Fi du bureau.");
        }

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
        $userIp = $this->getRealIp($request);
        
        // Sécurité IP (Admin et Local exemptés pour tests)
        $isAuthorized = app()->isLocal() || in_array($userIp, $this->allowedIps) || $user->role_id == 1;
        if (!$isAuthorized) {
            return back()->with('error', "Accès refusé. IP $userIp non reconnue.");
        }

        $presence = Presence::firstOrNew([
            'user_id' => $user->id,
            'date' => $now->toDateString()
        ]);

        // 1. POINTAGE DU MATIN
        if (!$presence->heure_matin) {
            // Règle : Pas de pointage avant 08:30
            if ($now->format('H:i') < '08:30') {
                return back()->with('error', "Le pointage ne commence qu'à 08:30.");
            }

            // Calcul du retard éventuel après 08:30
            $heureLimite = Carbon::parse($now->toDateString() . ' 08:30:00');
            $retardMinutes = 0;
            if ($now->gt($heureLimite)) {
                $retardMinutes = $heureLimite->diffInMinutes($now);
            }

            $presence->heure_matin = $now->format('H:i:s');
            $presence->type = $request->type ?? 'Bureau';
            $presence->present = true;
            $presence->save();

            $this->addScore($user->id, 0.5);

            if ($retardMinutes > 0) {
                return back()->with('warning_retard', "Pointage réussi, mais vous avez $retardMinutes minutes de retard !");
            }

            return back()->with('status', "Arrivée enregistrée à " . $now->format('H:i'));
        } 
        
        // 2. POINTAGE DU SOIR (SORTIE)
        elseif (!$presence->heure_soir) {
            $presence->heure_soir = $now->format('H:i:s');
            
            // Calcul total d'heures et déduction automatique de 1h (60 min)
            $heureArrivee = Carbon::parse($presence->heure_matin);
            $minutesTotales = $heureArrivee->diffInMinutes($now);
            $minutesTravaillees = max(0, $minutesTotales - 60);
            
            $presence->total_heures = round($minutesTravaillees / 60, 2);
            $presence->save();

            $this->addScore($user->id, 0.5);

            return back()->with('status', "Sortie enregistrée. 1h de pause déduite. Total : " . $presence->total_heures . "h");
        } 

        return back()->with('error', "Vous avez déjà terminé votre journée.");
    }

    private function getRealIp(Request $request) {
        $ip = $request->header('X-Forwarded-For');
        return $ip ? trim(explode(',', $ip)[0]) : $request->ip();
    }

    private function addScore($userId, $pts) {
        $score = WeeklyScore::firstOrCreate([
            'user_id' => $userId,
            'week_number' => now()->weekOfYear,
            'year' => now()->year,
        ]);
        // On incrémente le score (colonne points_presence)
        $score->increment('points_presence', $pts);
    }
}