@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8 bg-[#f8fafc] min-h-screen">
    
    {{-- EN-TÊTE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter">
            <span class="opacity-40">Profil de</span>
            <span class="ml-2 inline-block">{{ $user->name ?? 'Collaborateur' }}</span>
        </h1>
        <a href="{{ route('dashboard') }}" class="w-full md:w-auto text-center bg-white text-slate-700 px-6 py-2 font-bold border border-slate-200 shadow-sm hover:bg-slate-50 transition-all text-[10px] uppercase rounded-xl">
            Retour Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        {{-- COLONNE GAUCHE : INFOS & SCORE --}}
        <div class="bg-white p-6 md:p-8 shadow-sm border border-slate-200 rounded-[20px] flex flex-col h-auto">
            <div class="flex flex-col items-center text-center space-y-6 w-full">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-4xl md:text-5xl font-black shadow-lg rounded-[20px]">
                        {{ !empty($user->name) ? strtoupper(substr($user->name, 0, 1)) : '?' }}
                    </div>
                </div>
                <div class="w-full">
                    <h2 class="text-xl md:text-2xl font-black text-slate-900 uppercase tracking-wider break-words">
                        {{ $user->name ?? 'Nom Introuvable' }}
                    </h2>
                    <div class="flex flex-col items-center gap-2 mt-2">
                        <p class="text-[10px] text-blue-600 font-black uppercase tracking-[0.2em] px-3 py-1 bg-blue-50 inline-block rounded-lg">
                            Poste: {{ $user->position ?? 'Collaborateur' }}
                        </p>
                        {{-- AFFICHAGE DU MENTOR SI EXISTANT --}}
                        @if($user->mentor)
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                                <i class="fas fa-handshake mr-1 text-blue-500"></i> Accompagné par : {{ $user->mentor->name }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- GRILLE DE SCORE --}}
            <div class="mt-10 grid grid-cols-2 gap-px bg-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                <div class="bg-white p-4 text-center">
                    <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Missions</p>
                    <p class="text-2xl md:text-3xl font-black text-slate-900 mt-1">{{ $tasks ? $tasks->count() : 0 }}</p>
                </div>
                <div class="bg-white p-4 text-center">
                    <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Score Semaine</p>
                    @php 
                        $currentWeeklyRecord = \App\Models\WeeklyScore::where('user_id', $user->id)
                            ->where('week_number', now()->weekOfYear)
                            ->where('year', now()->year)
                            ->first();
                        $displayScore = $currentWeeklyRecord ? $currentWeeklyRecord->score : 0;

                        $heuresSemaine = \App\Models\Presence::where('user_id', $user->id)
                            ->whereBetween('date_pointage', [now()->startOfWeek(), now()->endOfWeek()])
                            ->sum('total_heures');
                        $pourcentageHeures = min(($heuresSemaine / 40) * 100, 100);
                    @endphp
                    <p class="text-2xl md:text-3xl font-black text-blue-600 mt-1">
                        {{ number_format($displayScore, 1) }}<span class="text-[10px] text-slate-400">/10</span>
                    </p>
                </div>
            </div>

            {{-- BARRE DES HEURES --}}
            <div class="mt-8 p-5 bg-slate-900 rounded-2xl text-white shadow-xl shadow-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Volume Hebdo</p>
                        <p class="text-2xl font-black text-blue-400">{{ number_format($heuresSemaine, 1) }}<span class="text-[10px] text-slate-500 ml-1">/ 40h</span></p>
                    </div>
                    <div class="h-8 w-8 bg-slate-800 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-[9px] font-black uppercase">
                        <span class="text-slate-500">Progression</span>
                        <span class="text-blue-400">{{ round($pourcentageHeures) }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full transition-all duration-700" style="width: {{ $pourcentageHeures }}%"></div>
                    </div>
                </div>
            </div>

            {{-- BOUTONS ACTIONS --}}
            @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                <div class="mt-10 flex flex-col gap-3">
                    <button id="open-message" class="w-full bg-slate-900 text-white py-4 font-black text-xs uppercase tracking-[0.2em] rounded-xl hover:bg-slate-800 transition-all shadow-md">Message</button>
                    <button id="open-eval" class="w-full bg-blue-600 text-white py-4 font-black text-xs uppercase tracking-[0.2em] rounded-xl hover:bg-blue-700 transition-all shadow-md">Évaluer</button>
                </div>
            @endif
        </div>

        {{-- COLONNE DROITE : MISSIONS & HISTORIQUE --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- LISTE DES MISSIONS --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-[20px] overflow-hidden">
                <div class="bg-slate-50 px-6 md:px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-[10px] md:text-sm font-black text-slate-900 uppercase tracking-[0.3em]">Missions récentes</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @if($tasks && $tasks->count() > 0)
                        @foreach($tasks->sortByDesc('created_at') as $task)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 hover:bg-slate-50 transition-all gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="text-blue-600 font-black text-xl">#</div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 uppercase tracking-wider">{{ $task->title }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1 uppercase">{{ Str::limit($task->description, 60) }}</div>
                                    </div>
                                </div>
                                <div class="self-start sm:self-center px-4 py-1 {{ $task->status === 'terminé' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-orange-50 text-orange-600 border-orange-100' }} border text-[9px] font-black uppercase tracking-widest rounded-lg">
                                    {{ $task->status }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="p-12 text-xs text-slate-400 font-black uppercase tracking-widest text-center italic">Aucune mission assignée pour le moment</p>
                    @endif
                </div>
            </div>

            {{-- HISTORIQUE DES ÉVALUATIONS (Correction sortByDesc sur null) --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-[20px] overflow-hidden">
                <div class="bg-slate-50 px-6 md:px-8 py-6 border-b border-slate-100">
                    <h3 class="text-[10px] md:text-sm font-black text-slate-900 uppercase tracking-[0.3em]">Historique des évaluations</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @if($user->performances && $user->performances->count() > 0)
                        @foreach($user->performances->sortByDesc('created_at')->take(5) as $perf)
                            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition-all">
                                <span class="text-[10px] md:text-sm font-black text-slate-600 uppercase tracking-widest">Évaluation du {{ $perf->created_at->format('d/m/Y') }}</span>
                                <span class="text-xl md:text-2xl font-black text-slate-900">{{ round($perf->score, 1) }}%</span>
                            </div>
                        @endforeach
                    @else
                        <p class="p-12 text-xs text-slate-400 font-black uppercase tracking-widest text-center italic">Aucun historique d'évaluation disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ÉVALUATION --}}
<div id="eval-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4 hidden">
    <div class="bg-white p-8 w-full max-w-lg border border-slate-200 shadow-2xl rounded-[20px]">
        <h3 class="text-xl font-black text-slate-900 uppercase mb-6 tracking-tighter text-center">Évaluer {{ $user->name }}</h3>
        <form action="{{ route('collaborator.evaluate', $user) }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] text-slate-400 font-black uppercase mb-1">Score de performance (0-100)</label>
                <input type="number" name="score" min="0" max="100" required class="w-full p-4 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 ring-blue-500 outline-none" />
            </div>
            <div>
                <label class="block text-[10px] text-slate-400 font-black uppercase mb-1">Commentaires de la direction</label>
                <textarea name="comment" rows="4" class="w-full p-4 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 ring-blue-500 outline-none"></textarea>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="button" id="cancel-eval" class="flex-1 px-4 py-4 bg-slate-100 text-slate-600 font-black text-[10px] uppercase rounded-xl">Annuler</button>
                <button type="submit" class="flex-1 px-4 py-4 bg-blue-600 text-white font-black text-[10px] uppercase rounded-xl shadow-lg">Enregistrer l'évaluation</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openEval = document.getElementById('open-eval');
        const cancelEval = document.getElementById('cancel-eval');
        const evalModal = document.getElementById('eval-modal');
        const openMessage = document.getElementById('open-message');

        if (openEval && evalModal) { 
            openEval.onclick = () => evalModal.classList.remove('hidden'); 
        }
        if (cancelEval && evalModal) { 
            cancelEval.onclick = () => evalModal.classList.add('hidden'); 
        }
        if (openMessage) {
            openMessage.onclick = () => { 
                window.location.href = "{{ route('messages') }}?user_id={{ $user->id }}"; 
            };
        }
    });
</script>
@endsection