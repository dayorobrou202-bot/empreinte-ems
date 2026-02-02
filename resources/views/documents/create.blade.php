@extends('layouts.dashboard')

@section('title', 'Ajouter et envoyer un document')

@section('inner-content')
<div class="max-w-xl mx-auto py-8">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h2 class="text-2xl font-black uppercase text-slate-900 mb-6">➕ Ajouter et envoyer un document</h2>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-black text-slate-900 uppercase mb-2">Titre</label>
                <input type="text" name="title" class="w-full rounded-md border border-slate-200 bg-white text-slate-900 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-black text-slate-900 uppercase mb-2">Fichier</label>
                <input type="file" name="document" class="w-full rounded-md border border-slate-200 bg-white text-slate-900 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-black text-slate-900 uppercase mb-2">Destinataire</label>
                <select name="recipient_id" class="w-full rounded-md border border-slate-200 bg-white text-slate-900 p-2" required>
                    <option value="">-- Choisir un collaborateur --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Envoyer</button>
        </form>
    </div>
</div>
@endsection
