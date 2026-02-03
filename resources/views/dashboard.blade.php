@extends('layouts.dashboard')

@section('inner-content')
<div class="min-h-screen py-4 md:py-8 px-4 md:px-0 max-w-5xl mx-auto space-y-10">
    
    {{-- STATS SECTION : Grille adaptative (1 col mobile, 2 col tablette, 4 col PC) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['Collaborateurs', $collaborateursCount ?? '7', 'fa-users', 'border-blue-600', 'text-blue-500'],
                ['Moyenne', ($avgScore ?? '89').'%', 'fa-chart-line', 'border-emerald-500', 'text-emerald-500'],
                ['Évaluations', $evaluationsCount ?? '0', 'fa-star', 'border-amber-500', 'text-amber-500'],
                ['Présences', $presenceCount ?? '1', 'fa-calendar-check', 'border-indigo-500', 'text-indigo-500']
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white p-5 shadow-xl shadow-slate-200/50 rounded-[24px] flex items-center justify-between border-l-[6px] {{ $stat[3] }} border-t border-r border-b border-slate-100 transition-transform hover:scale-[1.03]">
            <div class="space-y-1">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $stat[0] }}</p>
                <h4 class="text-2xl font-black text-slate-900 leading-none">{{ $stat[1] }}</h4>
            </div>
            <div class="w-10 h-10 bg-slate-50 flex items-center justify-center rounded-xl border border-slate-100 flex-shrink-0">
                <i class="fas {{ $stat[2] }} {{ $stat[4] }} text-lg"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- PERFORMANCE SECTION --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-7 bg-blue-600 rounded-full"></div>
                <h2 class="text-slate-900 font-black text-lg md:text-xl uppercase tracking-tighter">Classement Équipe</h2>
            </div>
            <span class="bg-blue-600/10 text-blue-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-blue-100">Live</span>
        </div>

        <div class="space-y-4">
            @foreach($topUsers as $index => $user)
                @php
                    $score = round($user->avgScore ?? $user->avg_score ?? 0);
                    $isMe = auth()->id() && auth()->id() === ($user->id ?? null);
                    $userPercent = $user->avgScore ?? $user->avg_score ?? $score;
                    
                    // Couleur de bordure dynamique pour le classement
                    $borderColor = 'border-slate-200';
                    $barColor = 'bg-blue-600';
                    if($index == 0) { $borderColor = 'border-yellow-400'; $barColor = 'bg-yellow-400'; }
                    if($index == 1) { $borderColor = 'border-slate-400'; $barColor = 'bg-slate-400'; }
                    if($index == 2) { $borderColor = 'border-amber-600'; $barColor = 'bg-amber-600'; }
                    if($isMe) { $borderColor = 'border-blue-600'; $barColor = 'bg-blue-600'; }
                @endphp

                {{-- Card Responsive : flex-col sur mobile, flex-row sur PC --}}
                <div class="group flex flex-col md:flex-row md:items-center justify-between p-4 md:p-6 bg-white shadow-lg shadow-slate-200/40 rounded-[28px] border-l-[8px] {{ $borderColor }} border-t border-r border-b border-slate-100 transition-all {{ $isMe ? 'ring-2 ring-blue-500/5' : '' }}">
                    
                    <div class="flex items-center gap-4 flex-1">
                        {{-- Position --}}
                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-slate-50 rounded-2xl border border-slate-100 shadow-inner">
                            @if($index == 0) <span class="text-2xl">🥇</span>
                            @elseif($index == 1) <span class="text-2xl">🥈</span>
                            @elseif($index == 2) <span class="text-2xl">🥉</span>
                            @else <span class="text-sm font-black text-slate-400">#{{ $index + 1 }}</span>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <a href="{{ route('collaborator.show', $user) }}" class="text-sm md:text-base font-black uppercase tracking-tight text-slate-900 hover:text-blue-600 truncate">
                                    {{ $user->name }}
                                </a>
                                @if($isMe) 
                                    <span class="text-[8px] bg-blue-600 text-white px-2 py-0.5 rounded-full font-black uppercase shadow-sm">Moi</span> 
                                @endif
                            </div>
                            
                            {{-- Jauge de score --}}
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden max-w-[200px]">
                                    <div class="h-full {{ $barColor }} rounded-full transition-all duration-1000" style="width: {{ $userPercent }}%"></div>
                                </div>
                                <span class="text-[11px] font-black text-slate-900">{{ $score }}%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Poste / Label (S'affiche en dessous sur mobile avec une bordure de séparation) --}}
                    <div class="mt-4 md:mt-0 flex items-center justify-between md:justify-end border-t md:border-t-0 border-slate-50 pt-3 md:pt-0">
                        <span class="text-[9px] font-black text-slate-400 uppercase bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 tracking-[0.1em]">
                            {{ $user->position ?? 'Collaborateur' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Assure que la bordure gauche est bien arrondie même avec Tailwind */
    .border-l-\[6px\], .border-l-\[8px\] {
        border-top-left-radius: 24px !important;
        border-bottom-left-radius: 24px !important;
    }
</style>
@endsection