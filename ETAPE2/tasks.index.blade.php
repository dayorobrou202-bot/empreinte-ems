@extends('layouts.app')
@section('title', 'Liste des tâches')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Liste des tâches</h2>

    @if(isset($tasks) && $tasks->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <ul class="space-y-4">
                @foreach($tasks as $task)
                    <li class="p-4 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $task->description }}</p>
                                <p class="text-xs text-gray-400 mt-2">Assignée à : {{ $task->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-mono text-sm">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</p>
                                <span class="mt-2 inline-block px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[12px] font-bold">{{ $task->status }}</span>
                            </div>
                        </div>
                        @if(auth()->check() && auth()->id() === $task->user_id && $task->status === 'en cours')
                            <div class="mt-4 flex justify-end">
                                <form action="{{ route('tasks.confirm', $task->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold">Marquer comme validée</button>
                                </form>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <p>Aucune tâche attribuée pour le moment.</p>
        </div>
    @endif

</div>
@endsection
