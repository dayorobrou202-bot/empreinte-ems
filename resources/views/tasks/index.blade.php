@extends('layouts.dashboard')

@section('inner-content')
<div class="max-w-4xl mx-auto py-8">

    {{-- En-tête principal --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-1 h-9 bg-blue-600 rounded-sm flex-shrink-0"></div>
        <h1 class="text-slate-900 font-black uppercase tracking-widest text-xl m-0">MES MISSIONS</h1>
    </div>

    {{-- Conteneur des tâches --}}
    <div>
        @forelse($tasks as $task)
            <div class="bg-white border border-slate-200/50 rounded-2xl p-5 mb-4 flex items-center justify-between shadow-sm">
                <div class="flex-1 min-w-0">
                    <div class="text-slate-900 font-black uppercase text-sm leading-tight truncate">{{ $task->title }}</div>
                    <div class="text-slate-400 text-sm mt-1 truncate uppercase">{{ $task->description ?? 'Aucune instruction' }}</div>
                </div>

                    <div class="flex flex-wrap items-center gap-6 ml-4">
                    <div class="text-right">
                        <div class="text-blue-600 font-mono font-bold text-sm">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</div>
                        <div class="text-slate-400 text-xs font-black uppercase">Échéance</div>
                    </div>

                    <div class="min-w-[140px] text-right">
                        @if($task->status === 'terminé')
                            <span class="inline-block border border-emerald-500 text-emerald-600 px-3 py-1 text-xs font-bold uppercase rounded-md bg-emerald-50">VALIDÉE</span>
                        @else
                            @if(auth()->check() && auth()->id() === $task->user_id)
                                <form action="{{ route('tasks.confirm', $task->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 text-white px-3 py-2 text-xs font-black uppercase rounded-xl">CONFIRMER</button>
                                </form>
                            @else
                                <span class="inline-block border border-blue-600 text-blue-600 px-3 py-1 text-xs font-bold uppercase rounded-md">EN COURS</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-slate-400 font-black uppercase tracking-wider">Aucune mission attribuée</div>
        @endforelse
    </div>

</div>
@endsection
