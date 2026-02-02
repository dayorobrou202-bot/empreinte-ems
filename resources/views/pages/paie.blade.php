@extends('layouts.dashboard')

@section('title', 'Paie — Empreinte')

@section('inner-content')
<div class="space-y-6 min-h-screen p-4 md:p-0">
    
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-gray-200 pb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Mes bulletins de <span class="text-blue-600">paie</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Historique personnel des versements</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
            ← Retour
        </a>
    </div>

    @if(empty($payrolls) || $payrolls->isEmpty())
        <div class="bg-white border border-gray-200 p-12 text-center rounded-xl shadow-sm">
            <p class="text-gray-500">Aucun bulletin disponible.</p>
        </div>
    @else
        <div class="hidden md:block bg-white border border-gray-200 shadow-sm overflow-hidden rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase">Période</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase text-center">Émission</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase text-center">Montant Net</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payrolls as $p)
                        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 flex items-center justify-center rounded text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ \Illuminate\Support\Str::title($p->month) }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 text-center text-sm text-gray-500">
                                {{ $p->created_at ? $p->created_at->translatedFormat('d M Y') : 'N/A' }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ number_format($p->amount, 0, ',', ' ') }} <span class="text-xs text-gray-400 font-normal">FCFA</span>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                @if(!empty($p->pdf_path))
                                    <a href="{{ asset('storage/' . $p->pdf_path) }}" target="_blank" class="inline-block px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors shadow-sm">
                                        Télécharger
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-4">
            @foreach($payrolls as $p)
                <div class="bg-white border border-gray-200 p-5 rounded-xl shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <span class="text-xs font-bold text-blue-600 uppercase">Bulletin</span>
                        <span class="text-xs text-gray-400">{{ $p->created_at ? $p->created_at->format('d/m/Y') : '' }}</span>
                    </div>
                    
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ \Illuminate\Support\Str::title($p->month) }}</h3>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($p->amount, 0, ',', ' ') }} <small class="text-xs text-gray-400 font-normal">FCFA</small></p>
                    </div>

                    @if(!empty($p->pdf_path))
                        <a href="{{ asset('storage/' . $p->pdf_path) }}" target="_blank" class="block w-full text-center py-3 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md active:bg-blue-700">
                            Ouvrir le document PDF
                        </a>
                    @else
                        <div class="text-center py-3 bg-gray-50 rounded-lg text-xs text-gray-400">Fichier indisponible</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection