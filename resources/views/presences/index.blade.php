@extends('layouts.dashboard')

@section('inner-content')
<div class="space-y-6 min-h-screen" style="font-family: 'Inter', sans-serif; padding: 10px;">
    
    @if(auth()->user()->isAdmin())
        {{-- =========================================================== --}}
        {{-- RESTAURATION DE TON ANCIENNE PAGE HISTORIQUE (VUE ADMIN)    --}}
        {{-- =========================================================== --}}
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

        {{-- TES FILTRES --}}
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

        {{-- TON ANCIEN TABLEAU COMPLET --}}
        <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase">Collaborateur</th>
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Matin</th>
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Midi</th>
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Soir</th>
                            <th class="p-5 text-[10px] font-bold text-blue-600 uppercase text-center bg-blue-50/30">Total Hebdo</th>
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-right">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50"> 
                        @foreach($presenceRows as $row)
                            @php
                                $cumulHebdo = \App\Models\Presence::where('user_id', $row->user->id)
                                    ->whereBetween('date_pointage', [now()->startOfWeek(), now()->endOfWeek()])
                                    ->sum('total_heures');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-5 flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-xs rounded-xl border border-slate-200">
                                        {{ strtoupper(substr($row->user->name, 0, 1)) }}
                                    </div>
                                    <div class="text-[14px] font-semibold text-slate-800">{{ $row->user->name }}</div>
                                </td>
                                <td class="p-5 text-center text-[11px] font-medium text-slate-700">{{ $row->heure_matin ?? '--:--' }}</td>
                                <td class="p-5 text-center text-[11px] font-medium text-slate-700">{{ $row->heure_midi ?? '--:--' }}</td>
                                <td class="p-5 text-center text-[11px] font-medium text-slate-700">{{ $row->heure_soir ?? '--:--' }}</td>
                                <td class="p-5 text-center bg-blue-50/30 font-black text-blue-700 text-[11px]">{{ number_format($cumulHebdo, 1) }}h</td>
                                <td class="p-5 text-right">
                                    <span class="px-3 py-1 border rounded-lg text-[9px] font-bold {{ $row->present ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50' }}">
                                        {{ $row->present ? 'PRÉSENT' : 'ABSENT' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- =========================================================== --}}
        {{-- TA VUE POINTAGE COLLABORATEUR (BOUTONS)                     --}}
        {{-- =========================================================== --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b-4 border-slate-100 pb-4">
            <h2 class="text-slate-900 font-black text-2xl uppercase tracking-[0.3em]">Pointage — Aujourd'hui</h2>
            <div class="text-sm text-slate-500 mt-2 md:mt-0">Heure actuelle : {{ $now->format('H:i') }}</div>
        </div>

        @php
    $ip_bureau = '127.0.0.1'; // On met une IP impossible
    $user_ip = request()->ip();
    // ON SUPPRIME LE 127.0.0.1 POUR LE TEST
    $est_au_bureau = ($user_ip === $ip_bureau); 
    @endphp

        <div class="mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($slotData as $key => $slot)
                    <div class="bg-white p-6 rounded-2xl text-center border border-slate-200/50 shadow-sm">
                        <h3 class="text-sm text-slate-400 font-black uppercase mb-2 tracking-widest">{{ $slot['label'] }}</h3>
                        @if($slot['pointed'])
                            <div class="text-slate-900 font-black text-2xl mb-2">{{ $slot['time'] ?? '—' }}</div>
                            <div class="text-xs text-slate-500 font-medium">Déjà pointé</div>
                        @else
                            <form action="{{ route('presences.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="slot" value="{{ $key }}">
                                @if($slot['active'])
                                    @if($est_au_bureau)
                                        <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-black shadow-lg">Pointer {{ $slot['label'] }}</button>
                                    @else
                                        <button type="button" disabled class="w-full bg-slate-50 text-slate-300 border-2 border-dashed border-slate-200 px-6 py-3 rounded-xl font-black">Verrouillé IP</button>
                                    @endif
                                @else
                                    <button type="button" disabled class="w-full bg-slate-100 text-slate-300 px-6 py-3 rounded-xl font-black opacity-60">Hors créneau</button>
                                @endif
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection