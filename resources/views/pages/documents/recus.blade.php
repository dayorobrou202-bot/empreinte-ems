@extends('layouts.dashboard')

@section('title', 'Documents reçus')

@section('inner-content')
<div class="space-y-6 min-h-screen p-4 md:p-0">
    
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-gray-200 pb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Documents <span class="text-blue-600">reçus</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Liste des fichiers qui vous ont été transmis</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
            ← Retour
        </a>
    </div>

    @php
        $user = auth()->user();
        $docs = collect();
        try { 
            if (\Illuminate\Support\Facades\Schema::hasColumn('documents', 'recipient_id')) { 
                $docs = \App\Models\Document::where('recipient_id', $user->id)
                        ->orderByDesc('created_at')
                        ->get(); 
            } 
        } catch (Exception $e) { $docs = collect(); }
    @endphp

    @if($docs->isEmpty())
        <div class="bg-white border border-gray-200 p-12 text-center rounded-xl shadow-sm">
            <p class="text-sm text-gray-500 italic">Aucun document reçu.</p>
        </div>
    @else
        <div class="hidden md:block bg-white border border-gray-200 shadow-sm overflow-hidden rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase">Titre du document</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase text-center">Date de réception</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($docs as $d)
                        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                            <td class="p-4 text-sm font-medium text-gray-900">
                                {{ $d->title ?? 'Document sans titre' }}
                            </td>
                            <td class="p-4 text-center text-sm text-gray-500">
                                {{ $d->created_at ? $d->created_at->translatedFormat('d F Y') : '—' }}
                            </td>
                            <td class="p-4 text-right">
                                @if(!empty($d->file_path))
                                    <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank" class="inline-block px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors shadow-sm">
                                        Ouvrir
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-4">
            @foreach($docs as $d)
                <div class="bg-white border border-gray-200 p-5 rounded-xl shadow-sm space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-gray-900">{{ $d->title ?? 'Document sans titre' }}</h3>
                            <p class="text-[11px] text-gray-500">{{ $d->created_at ? $d->created_at->translatedFormat('d F Y') : '' }}</p>
                        </div>
                        <div class="text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>

                    @if(!empty($d->file_path))
                        <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank" class="block w-full text-center py-3 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm active:bg-blue-700">
                            Consulter le fichier
                        </a>
                    @else
                        <div class="text-center py-2 bg-gray-50 rounded text-xs text-gray-400">Fichier indisponible</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
