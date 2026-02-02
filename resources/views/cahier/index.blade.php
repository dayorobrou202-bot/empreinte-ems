@extends('layouts.dashboard')

@section('inner-content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Cahier de charge — Présentation</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('cahier.pdf') }}" class="px-3 py-2 bg-gray-200 rounded" target="_blank">Ouvrir PDF</a>
            <a href="{{ route('cahier.pdf') }}" class="px-3 py-2 bg-blue-600 text-white rounded" download>Télécharger</a>
            <button onclick="document.getElementById('pdfFrame').contentWindow.print()" class="px-3 py-2 bg-green-600 text-white rounded">Imprimer</button>
        </div>
    </div>

    <div class="bg-white shadow rounded overflow-hidden border border-gray-100">
        <iframe id="pdfFrame" src="{{ route('cahier.pdf') }}" class="w-full h-[800px]" frameborder="0"></iframe>
    </div>

    <div class="mt-6 text-sm text-gray-600">
        <p>Si vous souhaitez que je reproduise ce document fidèlement en HTML/Tailwind (mêmes polices & graphiques), dites-moi si vous préférez le résultat en :</p>
        <ul class="list-disc ml-6 mt-2">
            <li>Vue Blade (`resources/views/cahier_reproduit.blade.php`) (intégrable à l'application)</li>
            <li>Fichier HTML autonome</li>
            <li>PDF exporté (après reproduction)</li>
        </ul>
    </div>
</div>
@endsection
