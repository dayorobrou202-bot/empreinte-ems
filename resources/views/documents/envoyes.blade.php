@extends('layouts.app')

@section('title', 'Documents envoyés')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-black-uppercase mb-6">📤 Documents envoyés</h2>
    <div class="card">
        <table class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="text-secondary text-xs uppercase">
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Destinataire</th>
                    <th class="px-4 py-2">Date d'envoi</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2">Facture.pdf</td>
                    <td class="px-4 py-2">Marie Martin</td>
                    <td class="px-4 py-2">14/01/2026</td>
                    <td class="px-4 py-2">
                        <a href="#" class="btn btn-primary">Voir</a>
                    </td>
                </tr>
                <!-- Autres lignes -->
            </tbody>
        </table>
    </div>
</div>
@endsection
