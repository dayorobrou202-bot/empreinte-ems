@extends('layouts.dashboard')

@section('title')
    {{ __('Dashboard Présences & Performance') }}
@endsection

@section('inner-content')
<div class="py-6 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-0 lg:px-0 space-y-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 uppercase">Ma Présence du Jour</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase">Signalement obligatoire : Matin, Midi et Soir</p>
                </div>
                <div class="bg-white text-blue-600 px-4 py-2 rounded-md text-xs font-black uppercase border border-slate-100">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>

            @php
                $todayPresence = \App\Models\Presence::where('user_id', auth()->id())
                                    ->whereDate('date_pointage', now()->toDateString())
                                    ->first();
            @endphp

            <form action="{{ route('presences.store') }}" method="POST">
                @csrf
                
                @if(!$todayPresence || !$todayPresence->heure_matin)
                <div class="flex justify-center gap-8 mb-8 p-4 bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="type" value="Bureau" checked class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="text-sm font-black text-slate-400 uppercase group-hover:text-blue-600 transition-colors">🏢 Au Bureau</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="type" value="Télétravail" class="w-5 h-5 text-purple-600 border-gray-300 focus:ring-purple-500">
                        <span class="text-sm font-black-uppercase text-slate-600 group-hover:text-purple-600 transition-colors">🏠 Télétravail</span>
                    </label>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                        <div class="relative group">
                        <div class="absolute -top-3 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase">Session 01</div>
                        <button type="submit" 
                            @if($todayPresence && $todayPresence->heure_matin) disabled @endif
                            class="w-full h-32 rounded-2xl border-2 flex flex-col items-center justify-center gap-2 transition-all
                            {{ ($todayPresence && $todayPresence->heure_matin) 
                                ? 'border-green-100 bg-green-50 text-green-600' 
                                : 'border-blue-600 bg-white text-blue-600 hover:bg-blue-600 hover:text-white shadow-lg shadow-blue-100' }}">
                            <span class="text-xs font-black uppercase">Matin</span>
                            <span class="text-lg font-black">{{ $todayPresence->heure_matin ? \Carbon\Carbon::parse($todayPresence->heure_matin)->format('H:i') : 'SIGNALER' }}</span>
                            @if($todayPresence && $todayPresence->heure_matin) <i class="fas fa-check-circle"></i> @endif
                        </button>
                    </div>

                    <div class="relative group">
                        <div class="absolute -top-3 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase">Session 02</div>
                        <button type="submit" 
                            @if(!$todayPresence || !$todayPresence->heure_matin || $todayPresence->heure_midi) disabled @endif
                            class="w-full h-32 rounded-[40px] border-2 flex flex-col items-center justify-center gap-2 transition-all
                            {{ ($todayPresence && $todayPresence->heure_midi) 
                                ? 'border-green-100 bg-green-50 text-green-600' 
                                : (!$todayPresence || !$todayPresence->heure_matin ? 'border-gray-100 bg-gray-50 text-gray-300 cursor-not-allowed' : 'border-blue-600 bg-white text-blue-600 hover:bg-blue-600 hover:text-white shadow-lg shadow-blue-100') }}">
                            <span class="text-xs font-black uppercase">Midi</span>
                            <span class="text-lg font-black">{{ $todayPresence->heure_midi ? \Carbon\Carbon::parse($todayPresence->heure_midi)->format('H:i') : 'SIGNALER' }}</span>
                            @if($todayPresence && $todayPresence->heure_midi) <i class="fas fa-check-circle"></i> @endif
                        </button>
                    </div>

                    <div class="relative group">
                        <div class="absolute -top-3 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase">Session 03</div>
                        <button type="submit" 
                            @if(!$todayPresence || !$todayPresence->heure_midi || $todayPresence->heure_soir) disabled @endif
                            class="w-full h-32 rounded-[40px] border-2 flex flex-col items-center justify-center gap-2 transition-all
                            {{ ($todayPresence && $todayPresence->heure_soir) 
                                ? 'border-green-100 bg-green-50 text-green-600' 
                                : (!$todayPresence || !$todayPresence->heure_midi ? 'border-gray-100 bg-gray-50 text-gray-300 cursor-not-allowed' : 'border-blue-600 bg-white text-blue-600 hover:bg-blue-600 hover:text-white shadow-lg shadow-blue-100') }}">
                            <span class="text-xs font-black uppercase">Soir</span>
                            <span class="text-lg font-black">{{ $todayPresence->heure_soir ? \Carbon\Carbon::parse($todayPresence->heure_soir)->format('H:i') : 'SIGNALER' }}</span>
                            @if($todayPresence && $todayPresence->heure_soir) <i class="fas fa-check-circle"></i> @endif
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <div class="card-dashboard p-8">
            <h3 class="text-lg font-black-uppercase text-gray-800 mb-6">🏆 Mon Classement & Performance</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    // Use controller-provided topUsers when available, otherwise compute a limited ranking via DB aggregate
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
                    @endphp
                    <div class="card-dashboard p-5 border {{ $isMe ? 'border-blue-600 bg-blue-50/30' : 'border-gray-100' }} flex items-center gap-4">
                        <span class="text-xl font-black {{ $index < 3 ? 'text-yellow-500' : 'text-gray-300' }}">#{{ $index + 1 }}</span>
                        <div class="flex-1">
                            <p class="text-sm font-black-uppercase {{ $isMe ? 'text-blue-600' : 'text-slate-700' }}">{{ $user->name }}</p>
                            <div class="w-full bg-gray-200 h-1.5 rounded-full mt-2">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $avgScore }}%"></div>
                            </div>
                        </div>
                        <span class="font-black text-xs">{{ round($avgScore) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection 