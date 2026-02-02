@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-12 px-4">
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight tracking-widest">NOUVEAU TRANSFERT</h1>
                <p class="text-slate-500 text-sm">Partagez vos documents avec l'équipe</p>
            </div>
            <div class="bg-indigo-50 p-3 rounded-xl">
                <i class="fas fa-paper-plane text-indigo-600 text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-cyan-400"></div>
            
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Objet du document</label>
                    <input type="text" name="title" required
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-indigo-500 focus:bg-white transition text-slate-700 font-bold outline-none shadow-sm"
                        placeholder="Ex: Rapport mensuel Janvier">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Destinataire</label>
                    <div class="relative">
                        <select name="recipient_id" required
                            class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-indigo-500 focus:bg-white transition text-slate-700 font-bold outline-none appearance-none shadow-sm">
                            <option value="">-- Choisir un collaborateur --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Fichier à joindre</label>
                    <div class="group relative flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer bg-slate-50/50 hover:bg-white hover:border-indigo-400 transition-all duration-300">
                        <input type="file" name="document" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-cloud-arrow-up text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-sm text-slate-700 font-bold">Cliquez pour choisir un fichier</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium italic">Format PDF, DOCX ou ZIP (Max 10Mo)</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-indigo-600 transition-all transform hover:-translate-y-1 shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                        <span>TRANSMETTRE LE DOCUMENT</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection