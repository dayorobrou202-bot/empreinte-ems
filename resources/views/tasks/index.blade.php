@extends('layouts.dashboard')

@section('inner-content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:py-8">

    {{-- EN-TÊTE --}}
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <div class="flex items-center gap-3">
            <div style="width: 4px; height: 32px; background-color: #2563eb; border-radius: 2px;"></div>
            <h1 class="text-slate-900 font-black uppercase tracking-widest text-lg sm:text-xl m-0">MES MISSIONS</h1>
        </div>
        <div class="hidden sm:block px-4 py-1.5 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase">
            STATUT : TEMPS RÉEL
        </div>
    </div>

    {{-- LISTE DES MISSIONS --}}
    <div class="space-y-4">
        @forelse($tasks as $task)
            @php
                $color = '#cbd5e1'; 
                if($task->status === 'en cours') {
                    $color = $task->is_overdue ? '#ef4444' : '#2563eb';
                } elseif($task->status === 'terminé') {
                    $color = '#10b981';
                }
            @endphp

            {{-- Carte responsive : flex-col sur mobile, flex-row sur desktop --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between shadow-sm relative overflow-hidden border border-slate-200" 
                 style="border-left: 8px solid {{ $color }} !important;">
                
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-slate-900 font-black uppercase text-sm tracking-tight break-words">{{ $task->title }}</span>
                        @if($task->status === 'en cours' && $task->is_overdue)
                             <span class="animate-pulse bg-rose-100 text-rose-600 text-[8px] font-black px-2 py-0.5 rounded-full uppercase">Retard</span>
                        @endif
                    </div>
                    
                    <div class="text-slate-400 text-[11px] mb-4 uppercase font-bold leading-relaxed">
                        {{ $task->description ?? 'Aucune consigne particulière' }}
                    </div>
                    
                    {{-- Dates : Grille sur mobile pour gagner de la place --}}
                    <div class="grid grid-cols-2 sm:flex sm:items-center gap-4 sm:gap-8 pt-3 border-t border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5">Assignée</span>
                            <span class="text-[10px] sm:text-[11px] font-black text-slate-600">{{ $task->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5">Échéance</span>
                            <span class="text-[10px] sm:text-[11px] font-black {{ $task->is_overdue ? 'text-rose-600' : 'text-blue-600' }}">
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Section Bouton : s'affiche en dessous sur mobile --}}
                <div class="mt-5 sm:mt-0 sm:ml-6 flex justify-end">
                    <div class="w-full sm:w-auto min-w-[140px]">
                        @if($task->status === 'terminé')
                            <div class="text-center sm:text-right">
                                <span class="inline-block px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase rounded-lg">Terminée</span>
                            </div>
                        @elseif($task->status === 'échoué')
                            <div class="text-center sm:text-right">
                                <span class="inline-block px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-black uppercase rounded-lg">Échec</span>
                            </div>
                        @else
                            <form action="{{ route('tasks.confirm', $task->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" 
                                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 text-[10px] font-black uppercase rounded-xl text-white transition-all shadow-md active:scale-95"
                                    style="background-color: {{ $color }}; border: none;">
                                    {{ $task->is_overdue ? 'Confirmer l\'échec' : 'Marquer comme fini' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="py-20 text-center">
                <p class="text-slate-300 font-black uppercase text-xs tracking-widest">Aucune mission pour le moment</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-pulse {
        animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection