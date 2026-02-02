@extends('layouts.dashboard')

@section('title', 'Envoyer un document')

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
    <h2 class="text-2xl font-bold mb-6">➕ Envoyer un document</h2>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form method="POST" action="#" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                 <label class="block text-gray-700 dark:text-gray-200 mb-2">Fichier</label>
                 <input type="file" name="document" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-4">
                 <label class="block text-gray-700 dark:text-gray-200 mb-2">Destinataire</label>
                 <input type="text" name="destinataire" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>
              <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Envoyer</button>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
           </form>
           <form method="POST" action="{{ route('documents.send') }}" enctype="multipart/form-data">
        </form>
    </div>
</div>
@endsection
