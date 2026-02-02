@extends('layouts.dashboard')

@section('inner-content')
<div class="p-8 min-h-screen">
    <h1 class="font-black-uppercase text-3xl mb-8">Assigner une <span class="text-blue-500">Mission</span></h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($collaborators as $collab)
        <div class="card-list p-6 hover:border-blue-500 transition-all group">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center font-bold text-xl text-white">
                    {{ substr($collab->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-black text-lg">{{ $collab->name }}</h3>
                    <p class="text-xs text-slate-500 font-black-uppercase">Collaborateur</p>
                </div>
            </div>
            
                <a href="{{ route('admin.tasks.create', $collab->id) }}" 
                    class="block w-full text-center btn btn-primary text-xs transition-all">
                Envoyer une tâche
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection