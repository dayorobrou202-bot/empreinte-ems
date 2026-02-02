@extends('layouts.dashboard')

@section('title', 'Créer une tâche pour ' . $user->name)

@section('inner-content')
<div class="max-w-xl mx-auto py-12">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-black-uppercase">Nouvelle mission : {{ $user->name }}</h2>
        <a href="{{ route('collaborator.show', $user->id) }}" class="text-sm text-secondary">Retour au profil</a>
    </div>

    <div class="card">
        {{-- L'action pointe vers la route store avec l'ID de l'utilisateur --}}
        <form method="POST" action="{{ route('admin.tasks.store', ['user' => $user->id]) }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm text-secondary mb-2">Titre de la mission</label>
                <input type="text" name="title" 
                       class="w-full input-charte" 
                       placeholder="Ex: Rédaction rapport mensuel" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm text-secondary mb-2">Description / Objectifs</label>
                <textarea name="description" rows="4" 
                          class="w-full input-charte" 
                          placeholder="Détails de la mission..." required></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm text-secondary mb-2">Date d'échéance</label>
                <input type="date" name="due_date" 
                       class="w-full input-charte" 
                       required>
            </div>

            <div class="flex items-center justify-end gap-4">
                <button type="reset" class="text-sm text-secondary">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    Enregistrer la mission
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
