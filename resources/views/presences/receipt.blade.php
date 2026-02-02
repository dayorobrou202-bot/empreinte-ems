@extends('layouts.dashboard')

@section('inner-content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <h1 class="text-xl font-black-uppercase mb-4">Reçu de signalement</h1>

        <div class="mb-4">
            <div class="text-sm text-secondary">Collaborateur</div>
            <div class="text-lg font-medium">{{ $log->user->name }} ({{ $log->user->email }})</div>
            <div class="text-sm text-secondary">Rôle: {{ $log->user->role->name ?? 'Collaborateur' }}</div>
        </div>

        <div class="mb-4">
            <div class="text-sm text-secondary">Action</div>
            <div class="text-lg">{{ $log->slot == 'canceled' ? 'Annulé' : 'Signalé' }}</div>
        </div>

        <div class="mb-4">
            <div class="text-sm text-secondary">Date et heure</div>
            <div class="text-lg">{{ \Illuminate\Support\Carbon::parse($log->occurred_at)->format('d/m/Y H:i:s') }}</div>
        </div>

        <div class="mt-6 flex items-center space-x-2">
            <a href="{{ route('admin.presences.index') }}" class="btn">Retour</a>
            <a href="#" onclick="window.print()" class="btn btn-primary">Imprimer le reçu</a>
        </div>
    </div>
</div>
@endsection
