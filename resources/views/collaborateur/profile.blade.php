@extends('layouts.dashboard')

@section('title', 'Profil - ' . $collaborator->name)

@section('inner-content')
    <div class="py-6 md:py-12"> <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-center gap-6"> <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-2xl font-bold shrink-0">
                        {{ substr($collaborator->name, 0, 1) }}
                    </div>
                    <div class="text-center md:text-left"> <div class="flex flex-col md:flex-row items-center gap-3">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $collaborator->name }}</h3>
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-1 rounded">
                                {{ $tasks->count() }} Missions
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">{{ $collaborator->email }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Score Moyen</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ round($averageScore, 1) }}%</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Évaluations</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $totalPerformances }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Dernière Mission</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-2">
                        {{ $tasks->first() ? $tasks->first()->created_at->diffForHumans() : 'Aucune' }}
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Missions et Objectifs</h3>
                    
                    @if(auth()->check() && auth()->user()->isAdmin() && isset($collaborator))
                        <a href="{{ route('admin.tasks.create', ['user' => $collaborator->id]) }}" 
                           class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                            + Nouvelle Mission
                        </a>
                    @endif
                </div>
                
                <div class="overflow-x-auto"> <table class="w-full min-w-[600px]"> <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">Titre</th>
                                <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">Échéance</th>
                                <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($tasks as $task)
                                <tr class="border-b border-gray-100 dark:border-gray-700">
                                    <td class="py-4 px-6 text-sm text-gray-800 dark:text-white font-medium">{{ $task->title }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status === 'terminé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-500">Aucune mission assignée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection