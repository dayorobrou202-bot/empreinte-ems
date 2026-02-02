@extends('layouts.app')

@section('title', 'Documents reçus')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-black mb-6">📥 Documents reçus</h2>
    <div class="bg-slate-50 p-4 rounded-xl shadow-sm border border-slate-100">
        <table class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="text-secondary text-xs uppercase">
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Expéditeur</th>
                    <th class="px-4 py-2">Date de réception</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2">Contrat.pdf</td>
                    <td class="px-4 py-2">Jean Dupont</td>
                    <td class="px-4 py-2">15/01/2026</td>
                    <td class="px-4 py-2">
                        <a href="#" class="btn btn-primary">Télécharger</a>
                    </td>
                </tr>
                <!-- Autres lignes -->
            </tbody>
        </table>
    </div>
</div>
@endsection
