@extends('layouts.dashboard')

@section('inner-content')
<div class="space-y-8 p-6">
    <h1 class="text-2xl font-black-uppercase text-slate-800 tracking-tighter">Performance de l'équipe</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Collaborateurs</p>
                <p class="text-3xl font-bold text-slate-800">{{ $totalCollaborateurs ?? 0 }}</p>
            </div>
            <i class="fas fa-users text-blue-600 bg-blue-50 p-3 rounded-xl"></i>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Missions</p>
                <p class="text-3xl font-bold text-slate-800">{{ $totalMissions ?? 0 }}</p>
            </div>
            <i class="fas fa-tasks text-purple-600 bg-purple-50 p-3 rounded-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 border-b">
                <tr>
                    <th class="p-5">Nom / Prénom</th>
                    <th class="p-5 text-center">Performance</th>
                    <th class="p-5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($employees as $emp)
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-5 font-bold text-slate-800 uppercase">{{ $emp->name }}</td>
                    <td class="p-5 text-center">
                        <div class="w-24 bg-gray-100 rounded-full h-1.5 mx-auto overflow-hidden">
                            <div class="bg-blue-600 h-full" style="width: 15%"></div>
                        </div>
                    </td>
                    <td class="p-5 text-right">
                        <a href="{{ route('collaborator.show', $emp->id) }}" class="text-blue-600 font-black text-[10px] uppercase border-b-2 border-blue-600 pb-1">Voir Profil</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection