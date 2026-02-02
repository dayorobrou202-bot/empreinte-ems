@extends('layouts.dashboard')

@section('title', 'Mes Préférences')

@section('inner-content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Message de succès -->
            @if (session('success'))
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                    <form method="POST" action="{{ route('preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="p-6">
                            <h3 class="text-lg font-black uppercase text-slate-900 mb-6">Personnalisez votre expérience</h3>

                            <!-- Thème -->
                            <div class="mb-6">
                                <label for="theme" class="block text-sm font-black text-slate-900 uppercase mb-2">Thème</label>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($themes as $value => $label)
                                        <label class="flex items-center p-4 border-2 rounded-[25px] cursor-pointer transition {{ $user->theme === $value ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300' }}">
                                            <input type="radio" name="theme" value="{{ $value }}" {{ $user->theme === $value ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                            <span class="ml-3 text-slate-800">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('theme')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Couleur Primaire -->
                            <div class="mb-6">
                                <label for="color_primary" class="block text-sm font-black text-slate-900 uppercase mb-2">Couleur Primaire</label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach ($colors as $value => $label)
                                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition
                                            @if ($user->color_primary === $value)
                                                border-gray-800
                                            @else
                                                border-gray-300
                                            @endif
                                        ">
                                            <input type="radio" name="color_primary" value="{{ $value }}" 
                                                {{ $user->color_primary === $value ? 'checked' : '' }} 
                                                class="w-4 h-4">
                                            <div class="ml-2 w-6 h-6 rounded-full border-2 border-gray-300
                                                @if ($value === 'blue') bg-blue-500
                                                @elseif ($value === 'red') bg-red-500
                                                @elseif ($value === 'green') bg-green-500
                                                @elseif ($value === 'purple') bg-purple-500
                                                @elseif ($value === 'indigo') bg-indigo-500
                                                @elseif ($value === 'pink') bg-pink-500
                                                @endif
                                            "></div>
                                            <span class="ml-2 text-sm text-slate-800">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('color_primary')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Position de la Barre Latérale -->
                            <div class="mb-6">
                                <label for="sidebar_position" class="block text-sm font-black text-slate-900 uppercase mb-2">Position de la Barre Latérale</label>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($positions as $value => $label)
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            @if ($user->sidebar_position === $value)
                                                border-blue-500 bg-blue-50
                                            @else
                                                border-gray-300 hover:border-blue-300
                                            @endif
                                        ">
                                            <input type="radio" name="sidebar_position" value="{{ $value }}" 
                                                {{ $user->sidebar_position === $value ? 'checked' : '' }} 
                                                class="w-4 h-4 text-blue-600">
                                            <span class="ml-3 text-slate-800">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('sidebar_position')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Afficher les Statistiques -->
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="show_statistics" value="1" 
                                        {{ $user->show_statistics ? 'checked' : '' }} 
                                        class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-3 text-sm text-slate-800">
                                        Afficher les cartes statistiques
                                    </span>
                                </label>
                            </div>

                            <!-- Afficher le Graphique de Performance -->
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="show_performance_chart" value="1" 
                                        {{ $user->show_performance_chart ? 'checked' : '' }} 
                                        class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-3 text-sm text-slate-800">
                                        Afficher le graphique de performance
                                    </span>
                                </label>
                            </div>

                            <!-- Nombre d'éléments par page -->
                            <div class="mb-6">
                                <label for="items_per_page" class="block text-sm font-black text-slate-900 uppercase mb-2">Nombre d'éléments par page</label>
                                <select name="items_per_page" id="items_per_page" class="w-full rounded-md border border-slate-200 bg-white text-slate-900 p-2">
                                    @for ($i = 5; $i <= 100; $i += 5)
                                        <option value="{{ $i }}" 
                                            {{ $user->items_per_page === $i ? 'selected' : '' }}>
                                            {{ $i }} éléments
                                        </option>
                                    @endfor
                                </select>
                                @error('items_per_page')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end gap-3 rounded-b-2xl">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-slate-200 rounded-md text-slate-700">Annuler</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white shadow-sm hover:bg-blue-700">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>

            <!-- Aperçu des préférences -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Aperçu du Thème</h4>
                    <div class="p-4 rounded border-2 border-gray-300 dark:border-gray-600
                        @if (old('theme', $user->theme) === 'dark')
                            bg-gray-900 text-gray-100
                        @else
                            bg-white text-gray-900
                        @endif
                    ">
                        <p class="font-medium">Aperçu du thème {{ old('theme', $user->theme) === 'dark' ? 'Sombre' : 'Clair' }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Couleur Primaire</h4>
                    <div class="flex gap-3 items-center">
                        <div class="w-12 h-12 rounded-lg border-2 border-gray-300
                            @if (old('color_primary', $user->color_primary) === 'blue') bg-blue-500
                            @elseif (old('color_primary', $user->color_primary) === 'red') bg-red-500
                            @elseif (old('color_primary', $user->color_primary) === 'green') bg-green-500
                            @elseif (old('color_primary', $user->color_primary) === 'purple') bg-purple-500
                            @elseif (old('color_primary', $user->color_primary) === 'indigo') bg-indigo-500
                            @elseif (old('color_primary', $user->color_primary) === 'pink') bg-pink-500
                            @endif
                        "></div>
                        <p class="text-gray-700 dark:text-gray-300">
                            Couleur sélectionnée : {{ $colors[old('color_primary', $user->color_primary)] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
