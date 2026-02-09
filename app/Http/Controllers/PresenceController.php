<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\User;
use App\Models\WeeklyScore;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceController extends Controller
{
    // Ajoute bien ton IP ici
    private $allowedIps = ['102.67.252.62', '160.155.123.45', '41.202.219.8'];

    public function page(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $userIp = $this->getRealIp($request);

        // Vérification de l'IP
        $isAtOffice = in_array($userIp, $this->allowedIps);

        // --- ASTUCE POUR TOI ---
        // Si tu es bloqué, on envoie l'IP à la vue pour l'afficher dans un message
        if (!$isAtOffice) {
            session()->now('warning', "IP non reconnue : $userIp. Connectez-vous au Wi-Fi du bureau.");
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
        
        $isAuthorized = in_array($userIp, $this->allowedIps);

        if (!app()->isLocal() && !$isAuthorized) {
            return back()->with('error', "Accès refusé. IP $userIp non reconnue.");
        }

        $presence = Presence::firstOrNew([
            'user_id' => $user->id,
            'date' => $now->toDateString()
        ]);

        if (!$presence->heure_matin) {
            $presence->heure_matin = $now->format('H:i:s');
            $presence->type = 'Bureau';
            $presence->present = true;
            $msg = "Arrivée enregistrée.";
            $this->addScore($user->id, 0.5);
        } elseif (!$presence->heure_soir) {
            $presence->heure_soir = $now->format('H:i:s');
            $diff = Carbon::parse($presence->heure_matin)->diffInMinutes($now);
            $presence->total_heures = round($diff / 60, 2);
            $msg = "Sortie enregistrée.";
            $this->addScore($user->id, 0.5);
        } else {
            return back()->with('error', "Déjà pointé aujourd'hui.");
        }

        $presence->save();
        return back()->with('status', $msg);
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
        $score->increment('points_presence', $pts);
        $score->save();
    }
}