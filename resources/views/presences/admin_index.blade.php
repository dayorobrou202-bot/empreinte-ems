@extends('layouts.dashboard')

@section('inner-content')
<div class="space-y-6 min-h-screen" style="font-family: 'Inter', sans-serif; padding: 10px;">
    
    {{-- ENTÊTE --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Historique <span class="text-blue-600">Présences</span>
            </h1>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Flux de présence administratif</p>
        </div>
        <a href="{{ route('admin.presences.export', request()->query()) }}" 
           class="w-full sm:w-auto text-center bg-white text-slate-900 px-6 py-2.5 font-bold text-[10px] uppercase border border-slate-300 rounded-xl shadow-sm hover:bg-slate-50 transition-all">
            Exporter CSV
        </a>
    </div>

    {{-- FILTRES --}}
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        <form method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-4 md:gap-8">
            <div class="flex flex-col gap-2 flex-1">
                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Collaborateur</label>
                <select name="user_id" style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; color:#0f172a; font-weight:600; font-size:11px; outline:none; width:100%;">
                    <option value="">Tous les membres</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request()->query('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Date</label>
                <input type="date" name="date" value="{{ $filterDate ?? date('Y-m-d') }}" 
                    style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; color:#0f172a; font-weight:600; font-size:11px; outline:none; width:100%;">
            </div>

            <div class="flex items-center gap-4 md:mt-5">
                <button type="submit" class="flex-1 md:flex-none text-blue-600 font-bold text-[10px] uppercase tracking-widest">Filtrer</button>
                <a href="{{ route('admin.presences.index') }}" class="flex-1 md:flex-none text-slate-400 font-bold text-[10px] uppercase tracking-widest text-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABLEAU --}}
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase">Collaborateur</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Arrivée</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Départ</th>
                        <th class="p-5 text-[10px] font-bold text-blue-600 uppercase text-center bg-blue-50/30">Total Heures</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50"> 
                    @foreach($presenceRows as $row)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-5 flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-xs rounded-xl border border-slate-200">
                                    {{ strtoupper(substr($row->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-[14px] font-semibold text-slate-800">{{ $row->user->name }}</div>
                                    <div class="text-[9px] text-slate-400 font-medium uppercase">ID: {{ $row->user->id }}</div>
                                </div>
                            </td>
                            
                            {{-- HEURE ARRIVÉE (SÉCURISÉE) --}}
                            <td class="p-5 text-center text-[11px] font-bold text-slate-700">
                                {{ isset($row->heure_matin) && $row->heure_matin ? \Carbon\Carbon::parse($row->heure_matin)->format('H:i') : '--:--' }}
                            </td>
                            
                            {{-- HEURE DÉPART (SÉCURISÉE) --}}
                            <td class="p-5 text-center text-[11px] font-bold text-slate-700">
                                {{ isset($row->heure_soir) && $row->heure_soir ? \Carbon\Carbon::parse($row->heure_soir)->format('H:i') : '--:--' }}
                            </td>

                            {{-- TOTAL CALCULÉ (SÉCURISÉ) --}}
                            <td class="p-5 text-center text-[12px] font-black text-blue-700 bg-blue-50/30">
                                {{ (isset($row->total_heures) && $row->total_heures > 0) ? number_format($row->total_heures, 2) . 'h' : '--' }}
                            </td>

                            <td class="p-5 text-right">
                                <span class="px-3 py-1 border rounded-lg text-[9px] font-bold {{ ($row->present ?? false) ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50' }}">
                                    {{ ($row->present ?? false) ? 'PRÉSENT' : 'ABSENT' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- VERSION MOBILE --}}
        <div class="md:hidden divide-y divide-slate-100">
            @foreach($presenceRows as $row)
                <div class="p-4 space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-[10px] rounded-lg border border-slate-200">
                                {{ strtoupper(substr($row->user->name, 0, 1)) }}
                            </div>
                            <div class="font-semibold text-slate-800 text-sm">{{ $row->user->name }}</div>
                        </div>
                        <span class="px-2 py-1 border rounded-md text-[8px] font-bold {{ ($row->present ?? false) ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50' }}">
                            {{ ($row->present ?? false) ? 'PRÉSENT' : 'ABSENT' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3 rounded-xl text-center">
                        <div>
                            <div class="text-[8px] text-slate-400 uppercase font-bold">Arrivée</div>
                            <div class="text-[11px] font-bold text-slate-700">{{ isset($row->heure_matin) ? \Carbon\Carbon::parse($row->heure_matin)->format('H:i') : '--:--' }}</div>
                        </div>
                        <div>
                            <div class="text-[8px] text-slate-400 uppercase font-bold">Départ</div>
                            <div class="text-[11px] font-bold text-slate-700">{{ isset($row->heure_soir) ? \Carbon\Carbon::parse($row->heure_soir)->format('H:i') : '--:--' }}</div>
                        </div>
                        <div class="border-l border-slate-200">
                            <div class="text-[8px] text-blue-500 uppercase font-black">Total</div>
                            <div class="text-[11px] font-black text-blue-700">{{ (isset($row->total_heures) && $row->total_heures > 0) ? number_format($row->total_heures, 1) . 'h' : '--' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

