@extends('layouts.dashboard')

@section('title')
    {{ __('Dashboard Présences & Performance') }}
@endsection

@section('inner-content')
<div class="py-6 md:py-10 min-h-screen max-w-6xl mx-auto px-4 space-y-10">
    
    {{-- SECTION POINTAGE : STYLE "PILULE" --}}
    <div class="bg-white rounded-[40px] shadow-xl shadow-slate-200/50 border border-white p-6 md:p-8 relative overflow-hidden">
        {{-- Barre décorative --}}
        <div class="absolute left-0 top-10 bottom-10 w-1.5 bg-blue-600 rounded-full m-1"></div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 ml-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Ma Présence</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Pointage obligatoire : Matin • Midi • Soir</p>
            </div>
            <div class="bg-slate-50 text-blue-600 px-5 py-2 rounded-2xl text-[10px] font-black uppercase border border-slate-100 shadow-sm">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        @php
            $todayPresence = \App\Models\Presence::where('user_id', auth()->id())
                                ->whereDate('date_pointage', now()->toDateString())
                                ->first();
        @endphp

        <form action="{{ route('presences.store') }}" method="POST" class="ml-4">
            @csrf
            
            @if(!$todayPresence || !$todayPresence->heure_matin)
            <div class="flex flex-wrap gap-6 mb-8 p-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="type" value="Bureau" checked class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest group-hover:text-blue-600 transition-colors">🏢 Au Bureau</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="type" value="Télétravail" class="w-4 h-4 text-purple-600 border-slate-300 focus:ring-purple-500">
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest group-hover:text-purple-600 transition-colors">🏠 Télétravail</span>
                </label>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Session 01 : Matin --}}
                <div class="relative group">
                    <button type="submit" 
                        @if($todayPresence && $todayPresence->heure_matin) disabled @endif
                        class="w-full h-32 rounded-[32px] border-l-[8px] flex flex-col items-center justify-center gap-2 transition-all
                        {{ ($todayPresence && $todayPresence->heure_matin) 
                            ? 'border-emerald-500 bg-emerald-50 text-emerald-600' 
                            : 'border-blue-600 bg-white text-blue-600 hover:scale-[1.02] shadow-lg shadow-blue-100' }}">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Matin</span>
                        <span class="text-xl font-black tracking-tighter">{{ $todayPresence->heure_matin ? \Carbon\Carbon::parse($todayPresence->heure_matin)->format('H:i') : 'SIGNALER' }}</span>
                        @if($todayPresence && $todayPresence->heure_matin) <i class="fas fa-check-circle"></i> @endif
                    </button>
                </div>

                {{-- Session 02 : Midi --}}
                <div class="relative group">
                    <button type="submit" 
                        @if(!$todayPresence || !$todayPresence->heure_matin || $todayPresence->heure_midi) disabled @endif
                        class="w-full h-32 rounded-[32px] border-l-[8px] flex flex-col items-center justify-center gap-2 transition-all
                        {{ ($todayPresence && $todayPresence->heure_midi) 
                            ? 'border-emerald-500 bg-emerald-50 text-emerald-600' 
                            : (!$todayPresence || !$todayPresence->heure_matin ? 'border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed opacity-50' : 'border-blue-600 bg-white text-blue-600 hover:scale-[1.02] shadow-lg shadow-blue-100') }}">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Midi</span>
                        <span class="text-xl font-black tracking-tighter">{{ $todayPresence->heure_midi ? \Carbon\Carbon::parse($todayPresence->heure_midi)->format('H:i') : 'SIGNALER' }}</span>
                        @if($todayPresence && $todayPresence->heure_midi) <i class="fas fa-check-circle"></i> @endif
                    </button>
                </div>

                {{-- Session 03 : Soir --}}
                <div class="relative group">
                    <button type="submit" 
                        @if(!$todayPresence || !$todayPresence->heure_midi || $todayPresence->heure_soir) disabled @endif
                        class="w-full h-32 rounded-[32px] border-l-[8px] flex flex-col items-center justify-center gap-2 transition-all
                        {{ ($todayPresence && $todayPresence->heure_soir) 
                            ? 'border-emerald-500 bg-emerald-50 text-emerald-600' 
                            : (!$todayPresence || !$todayPresence->heure_midi ? 'border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed opacity-50' : 'border-blue-600 bg-white text-blue-600 hover:scale-[1.02] shadow-lg shadow-blue-100') }}">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Soir</span>
                        <span class="text-xl font-black tracking-tighter">{{ $todayPresence->heure_soir ? \Carbon\Carbon::parse($todayPresence->heure_soir)->format('H:i') : 'SIGNALER' }}</span>
                        @if($todayPresence && $todayPresence->heure_soir) <i class="fas fa-check-circle"></i> @endif
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- SECTION PERFORMANCE : STYLE "TOP CLASSEMENT" --}}
    <div class="space-y-6 px-2">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-6 bg-slate-900 rounded-full"></div>
            <h3 class="text-lg font-black text-slate-900 uppercase tracking-widest">🏆 Classement & Performance</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $rankingSource = $topUsers ?? null;
                if (!$rankingSource) {
                    $rankingSource = \App\Models\User::leftJoin('performances', 'performances.user_id', 'users.id')
                        ->select('users.*', \Illuminate\Support\Facades\DB::raw('COALESCE(AVG(performances.score), 0) as avg_score'))
                        ->groupBy('users.id')
                        ->orderByDesc('avg_score')
                        ->limit(6)
                        ->get();
                }
            @endphp

            @foreach($rankingSource as $index => $user)
                @php
                    $avgScore = isset($user->avg_score) ? $user->avg_score : ($user->performances->avg('score') ?? 0);
                    $isMe = $user->id === auth()->id();
                    
                    // Couleur de bordure dynamique
                    $borderColor = 'border-slate-100';
                    if($index == 0) $borderColor = 'border-yellow-400';
                    elseif($index == 1) $borderColor = 'border-slate-400';
                    elseif($index == 2) $borderColor = 'border-amber-600';
                    if($isMe) $borderColor = 'border-blue-600';
                @endphp

                <div class="bg-white p-5 rounded-[28px] border-l-[8px] shadow-lg shadow-slate-200/40 border-t border-r border-b {{ $borderColor }} flex items-center gap-4 transition-transform hover:translate-y-[-4px]">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-slate-50 rounded-2xl font-black text-slate-900 text-sm">
                        @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else #{{ $index + 1 }} @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-black uppercase tracking-tight {{ $isMe ? 'text-blue-600' : 'text-slate-700' }} truncate">
                            {{ $user->name }}
                        </p>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ $avgScore }}%"></div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <span class="block text-xs font-black text-slate-900">{{ round($avgScore) }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Force l'arrondi gauche sur les bordures épaisses */
    .border-l-\[8px\] {
        border-top-left-radius: 30px !important;
        border-bottom-left-radius: 30px !important;
    }
</style>
@endsection