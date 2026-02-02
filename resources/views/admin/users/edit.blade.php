@extends('layouts.dashboard')

@section('inner-content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à la liste
            </a>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Modifier l'utilisateur</h1>
            <p class="text-slate-600 dark:text-slate-300">Mettez à jour les informations de l'utilisateur</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Profil utilisateur -->
                <div class="mb-8 pb-8 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">ID: {{ $user->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Nom -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('email') border-red-500 @enderror">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mot de passe (optionnel) -->
                <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                    <label for="password" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('password') border-red-500 @enderror"
                        placeholder="Laissez vide pour ne pas changer">
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">Minimum 8 caractères si vous changez le mot de passe</p>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('password_confirmation') border-red-500 @enderror"
                        placeholder="Confirmez si vous changez le mot de passe">
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Rôle -->
                <div class="mb-8">
                    <label for="role_id" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Rôle</label>
                    <select name="role_id" id="role_id" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('role_id') border-red-500 @enderror">
                        @forelse ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @empty
                            <option disabled>Aucun rôle disponible</option>
                        @endforelse
                    </select>
                    @error('role_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror

                    @if($user->isAdmin() && \App\Models\User::where('role_id', 1)->count() === 1)
                        <p class="text-yellow-600 dark:text-yellow-400 text-sm mt-2 flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Cet utilisateur est le dernier administrateur, impossible de modifier son rôle
                        </p>
                    @endif
                </div>

                <!-- Informations supplémentaires -->
                <div class="mb-8 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        <span class="font-semibold">Créé le:</span> {{ $user->created_at->format('d/m/Y à H:i') }}<br>
                        <span class="font-semibold">Dernière modification:</span> {{ $user->updated_at->format('d/m/Y à H:i') }}
                    </p>
                </div>

                <!-- Boutons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        Mettre à jour
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 px-6 py-3 bg-slate-200 text-slate-800 rounded-lg font-semibold hover:bg-slate-300 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition duration-200 text-center">
                        Annuler
                    </a>
                </div>
            </form>
        </div>

        <!-- Zone de danger -->
        <div class="mt-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900 rounded-lg p-6">
            <h3 class="font-semibold text-red-900 dark:text-red-300 mb-3">⚠️ Zone de danger</h3>
            <p class="text-red-800 dark:text-red-200 text-sm mb-4">
                La suppression de cet utilisateur est une action irréversible. Toutes les données associées seront supprimées.
            </p>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer cet utilisateur ? Cette action ne peut pas être annulée.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition duration-200">
                    Supprimer cet utilisateur
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
