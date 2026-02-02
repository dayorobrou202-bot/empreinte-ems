@extends('layouts.dashboard')

@section('title')
    Éditer la tâche de {{ $user->name }}
    <a href="{{ route('admin.tasks.index', $user) }}" class="ml-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Retour</a>
@endsection

@section('inner-content')
    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form method="POST" action="{{ route('admin.tasks.update', [$user, $task]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 mb-2">Titre</label>
                        <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        @error('title')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 mb-2">Description</label>
                        <textarea name="description" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('description', $task->description) }}</textarea>
                        @error('description')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 mb-2">Statut</label>
                        <select name="status" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                            <option value="en attente" @if($task->status == 'en attente') selected @endif>En attente</option>
                            <option value="en cours" @if($task->status == 'en cours') selected @endif>En cours</option>
                            <option value="terminée" @if($task->status == 'terminée') selected @endif>Terminée</option>
                        </select>
                        @error('status')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mettre à jour</button>
                </form>
                <form method="POST" action="{{ route('admin.tasks.destroy', [$user, $task]) }}" class="mt-4" onsubmit="return confirm('Supprimer cette tâche ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
