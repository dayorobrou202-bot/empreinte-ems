@extends('layouts.dashboard')
@section('inner-content')
<div class="py-12">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-6 uppercase text-indigo-700 underline">Nouvelle mission pour {{ $user->name }}</h2>
        <form action="{{ route('admin.tasks.store', $user->id) }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block font-bold uppercase text-xs mb-2">Titre de la mission</label>
                <input type="text" name="title" required class="w-full border-gray-300 rounded-md shadow-sm p-3">
            </div>
            <div class="mb-5">
                <label class="block font-bold uppercase text-xs mb-2">Description / Instructions</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm p-3"></textarea>
            </div>
            <div class="mb-5">
                <label class="block font-bold uppercase text-xs mb-2">Date limite d'échéance</label>
                <input type="date" name="due_date" required class="w-full border-gray-300 rounded-md shadow-sm p-3">
            </div>
            <button type="submit" class="w-full bg-green-600 text-white font-black py-4 rounded uppercase shadow hover:bg-green-700">
                Enregistrer la tâche
            </button>
        </form>
    </div>
</div>
@endsection