@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <div class="flex flex-col">
        <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">Dashboard</h2>
        <p class="text-slate-500 font-medium">Contrôlez vos activités et votre messagerie.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/50 transition-transform hover:scale-105">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tâches en cours</p>
                    <p class="text-4xl font-black text-slate-900">08</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/50 transition-transform hover:scale-105">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                    <i class="fas fa-comments text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Messages</p>
                    <p class="text-4xl font-black text-slate-900">03</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/50 transition-transform hover:scale-105">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                    <i class="fas fa-calendar-alt text-xl"></i>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Congés</p>
                    <p class="text-4xl font-black text-slate-900">14</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <h3 class="text-slate-900 font-black uppercase text-sm tracking-widest mb-6">Performance Équipe</h3>
        <div class="bg-white rounded-2xl p-8 border border-slate-200/50 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">
                <span>Collaborateur</span>
                <span>Score</span>
            </div>
            <div class="mt-4 border-t border-slate-100 pt-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-bold text-slate-900 shadow-sm text-xs">A</div>
                    <div class="flex flex-col">
                        <span class="text-slate-900 font-black text-sm uppercase">ange</span>
                        <span class="text-slate-500 text-[9px] font-bold uppercase">Collaborateur</span>
                    </div>
                </div>
                <span class="text-blue-600 font-black text-xl">100%</span>
            </div>
        </div>
    </div>
</div>
@endsection