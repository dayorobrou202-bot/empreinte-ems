@extends('layouts.dashboard')

@section('inner-content')
<div class="max-w-6xl mx-auto p-8">
    @php
        // rechercher les images extraites du PDF dans public/images/cahier
        $imgs = glob(public_path('images/cahier/*.{png,jpg,jpeg}'), GLOB_BRACE) ?: [];
        // trier naturellement
        natsort($imgs);
    @endphp
    <!-- Cover -->
    <div class="bg-white rounded-lg shadow p-10 mb-8">
        <div class="flex items-start gap-6">
            <div class="flex-1">
                <h1 class="text-4xl font-extrabold mb-2">Présentation de l’application de gestion collaborative</h1>
                <p class="text-gray-600 mb-4">Cahier des charges — Version reproduite</p>
                <div class="text-sm text-gray-500 mb-6">Auteur: Équipe Projet • Date: 2026-03</div>

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="p-4 border rounded">
                        <h3 class="font-semibold">But</h3>
                        <p class="text-sm text-gray-600">Fournir une solution de gestion collaborative pour le suivi des tâches, présences et documents.</p>
                    </div>
                    <div class="p-4 border rounded">
                        <h3 class="font-semibold">Public</h3>
                        <p class="text-sm text-gray-600">Administrateurs, collaborateurs et managers.</p>
                    </div>
                </div>
            </div>
            <div class="w-56 text-center">
                <img src="/images/cahier/cover-logo.png" alt="logo" class="mx-auto w-40 h-40 object-contain" onerror="this.style.display='none'">
                <div class="text-xs text-gray-400 mt-2">Logo (placer dans public/images/cahier/cover-logo.png)</div>
            </div>
        </div>
    </div>

    <!-- Table of contents & sections -->
    <div class="grid grid-cols-3 gap-6">
        <aside class="col-span-1">
            <div class="sticky top-20 bg-white p-4 rounded shadow">
                <h4 class="font-bold mb-3">Sommaire</h4>
                <ol class="text-sm text-gray-600 list-decimal ml-5 space-y-2">
                    <li><a href="#intro" class="text-blue-600">Introduction</a></li>
                    <li><a href="#features" class="text-blue-600">Fonctionnalités</a></li>
                    <li><a href="#ux" class="text-blue-600">Design & UX</a></li>
                    <li><a href="#tech" class="text-blue-600">Architecture technique</a></li>
                    <li><a href="#planning" class="text-blue-600">Planning</a></li>
                </ol>
            </div>
        </aside>

        <main class="col-span-2">
            <section id="intro" class="mb-8 bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-semibold mb-3">Introduction</h2>
                <p class="text-gray-700">Ce document présente les spécifications principales de l'application. La reproduction ici reprend la structure et le graphisme du PDF fourni, adaptée pour le web.</p>
            </section>

            <section id="features" class="mb-8 bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-semibold mb-3">Fonctionnalités principales</h2>
                <ul class="list-disc ml-5 text-gray-700 space-y-2">
                    <li>Gestion des présences (signalement matin/midi/soir)</li>
                    <li>Notifications administrateur (historique email/action/heure)</li>
                    <li>Gestion des tâches, documents, congés</li>
                    <li>Tableau de bord collaborateur et admin</li>
                </ul>
            </section>

            <section id="ux" class="mb-8 bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-semibold mb-3">Design & Graphisme</h2>
                <p class="text-gray-700 mb-4">La palette et les typographies sont calquées sur le PDF. Remplacez les images dans <span class="font-mono">public/images/cahier/</span> pour un rendu identique.</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border rounded p-3">
                        <img src="/images/cahier/sample-1.png" alt="sample" class="w-full h-36 object-cover rounded" onerror="this.style.display='none'">
                    </div>
                    <div class="border rounded p-3">
                        <img src="/images/cahier/sample-2.png" alt="sample2" class="w-full h-36 object-cover rounded" onerror="this.style.display='none'">
                    </div>
                </div>
            </section>

            <section id="tech" class="mb-8 bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-semibold mb-3">Architecture technique</h2>
                <p class="text-gray-700">Laravel + Tailwind CSS + Vite. La reproduction utilise les composants Blade existants et les classes utilitaires Tailwind pour faciliter l'intégration.</p>
            </section>

            <section id="planning" class="mb-8 bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-semibold mb-3">Planning</h2>
                <p class="text-gray-700">Proposition de découpage en sprints, livrables et jalons présentés ici.</p>
            </section>
        </main>
    </div>

    @if(count($imgs))
        <div class="mt-8 space-y-8">
            @foreach($imgs as $img)
                @php $url = str_replace(public_path(), '', $img); $url = ltrim(str_replace('\\','/',$url), '/'); @endphp
                <div class="bg-white shadow rounded overflow-hidden border border-gray-100">
                    <img src="/{{ $url }}" alt="page" class="w-full object-contain">
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-8 text-sm text-gray-500">Aucune image trouvée — placez les pages exportées dans <code>public/images/cahier/</code> (ex: page-001.png)</div>
    @endif

</div>

@endsection

</div>

@endsection
