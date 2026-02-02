@extends('layouts.dashboard')

@section('inner-content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase">Historique des présences</h1>
            <p class="text-[10px] font-black text-slate-400 uppercase mt-1">Suivi automatisé des pointages quotidiens</p>
        </div>
        <div class="flex flex-col items-end">
            <span class="text-[10px] font-black uppercase text-blue-600">Mois en cours</span>
            <span class="text-lg font-black text-slate-900">{{ now()->translatedFormat('F Y') }}</span>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-x-auto">
        <div class="min-w-[720px]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="p-4 text-[10px] font-black text-slate-400">Date</th>
                    <th class="p-4 text-[10px] font-black text-slate-400 text-center">Heure</th>
                    <th class="p-4 text-[10px] font-black text-slate-400 text-center">Créneau</th>
                    <th class="p-4 text-[10px] font-black text-slate-400 text-right">Type</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach($logs as $log)
                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                            <span class="text-sm font-black text-slate-900 uppercase">
                                {{ \Carbon\Carbon::parse($log->occurred_at)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-black">
                            {{ \Carbon\Carbon::parse($log->occurred_at)->format('H:i') }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-black">
                            {{ ucfirst($log->slot ?? '—') }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black-uppercase {{ ($log->type ?? 'Bureau') == 'Télétravail' ? 'bg-purple-50 text-purple-600' : 'bg-green-50 text-green-600' }}">
                            {{ $log->type ?? 'Bureau' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    <div class="mt-4">
        @if(isset($logs) && method_exists($logs, 'links'))
            {{ $logs->links() }}
        @endif
    </div>
</div>
@endsection
