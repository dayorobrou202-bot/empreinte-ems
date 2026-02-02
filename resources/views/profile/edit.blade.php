@extends('layouts.dashboard')

@section('title', 'Profil')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Affichage du Rôle -->
            <div class="card">
                <div class="max-w-xl">
                    <h3 class="text-lg font-black-uppercase mb-4">{{ __('Role Information') }}</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Your Role') }}</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">
                                    @if(auth()->user()->role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if(auth()->user()->role_id === 1)
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif(auth()->user()->role_id === 2)
                                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @else
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @endif
                                        ">
                                            {{ auth()->user()->role->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">{{ __('No role assigned') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Role ID') }}: {{ auth()->user()->role_id }}</p>
                                @if(auth()->user()->role)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ auth()->user()->role->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
