@extends('layouts.app')

@section('title', 'Liste des tâches')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    
    {{-- TITRE DE SECTION --}}
    <div class="mb-8 px-2">
        <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">Mes Missions</h2>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Suivi de tes objectifs et tâches assignées</p>
    </div>

    @if(isset($tasks) && $tasks->isNotEmpty())
        {{-- LISTE DES TÂCHES --}}
        <div class="space-y-6">
            @foreach($tasks as $task)
                <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/50 border border-white p-6 md:p-8 transition-transform hover:scale-[1.01]">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                                <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">{{ $task->title }}</h3>
                            </div>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed mb-4">{{ $task->description }}</p>
                            
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="text-[10px] font-black text-slate-400 uppercase bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                </span>
                                <span class="text-[10px] font-black text-slate-400 uppercase bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                                    <i class="far fa-user mr-1"></i> {{ $task->user->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-4 w-full md:w-auto border-t md:border-t-0 pt-4 md:pt-0">
                            <span class="px-4 py-1 bg-blue-50 text-blue-600 rounded-full text-[11px] font-black uppercase tracking-widest border border-blue-100">
                                {{ $task->status }}
                            </span>

                            @if(auth()->check() && auth()->id() === $task->user_id && $task->status === 'en cours')
                                <form action="{{ route('tasks.confirm', $task->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                                        Valider la tâche
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- ÉTAT VIDE (EMPTY STATE) - CORRESPOND À TON IMAGE --}}
        <div class="bg-white rounded-[40px] shadow-xl shadow-slate-200/50 border border-white p-20 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-[28px] flex items-center justify-center mb-6 border border-slate-100">
                <i class="fas fa-clipboard-check text-slate-200 text-3xl"></i>
            </div>
            <p class="text-xl font-bold text-slate-400 uppercase tracking-tight">Aucune tâche attribuée</p>
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mt-2">Reviens plus tard pour de nouvelles missions</p>
        </div>
    @endif

</div>
@endsection
