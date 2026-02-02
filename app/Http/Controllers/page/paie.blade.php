@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-black uppercase italic text-slate-800">Mes bulletins de paie</h1>
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600">Retour au tableau</a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        @if($payrolls->isEmpty())
            <div class="p-12 text-center">
                <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-slate-500 font-medium italic">Aucun bulletin de paie disponible pour le moment.</p>
            </div>
        @else
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">Période</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">Montant Net</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">Statut</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-right">Document</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($payrolls as $payroll)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ \Carbon\Carbon::parse($payroll->month)->translatedFormat('F Y') }}
                            </td>
                            <td class="px-6 py-4 font-black text-blue-600">
                                {{ number_format($payroll->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full">Payé</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($payroll->pdf_path)
                                    <a href="{{ asset('storage/' . $payroll->pdf_path) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        PDF
                                    </a>
                                @else
                                    <span class="text-slate-300 italic text-sm">Non disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection