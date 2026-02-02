@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-black italic uppercase text-white mb-8">Demander un congé</h1>

    <div class="bg-slate-900 p-8 rounded-3xl shadow-xl max-w-2xl">
        <form action="{{ route('conges.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-black uppercase italic text-xs text-slate-500 ml-2">Date de début</label>
                    <input type="date" name="start_date" required class="w-full bg-slate-800 border-none rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="space-y-2">
                    <label class="font-black uppercase italic text-xs text-slate-500 ml-2">Date de fin</label>
                    <input type="date" name="end_date" required class="w-full bg-slate-800 border-none rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="space-y-2">
                <label class="font-black uppercase italic text-xs text-slate-500 ml-2">Type de congé</label>
                <select name="type" class="w-full bg-slate-800 border-none rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="annuel">Congé Annuel</option>
                    <option value="maladie">Maladie</option>
                    <option value="exceptionnel">Exceptionnel</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="font-black uppercase italic text-xs text-slate-500 ml-2">Motif (Optionnel)</label>
                <textarea name="reason" rows="3" class="w-full bg-slate-800 border-none rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-blue-500" placeholder="Expliquez brièvement..."></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black italic uppercase py-4 rounded-2xl transition-all shadow-lg shadow-blue-900/20">
                Envoyer la demande
            </button>
        </form>
    </div>
</div>
@endsection