@extends('layouts.dashboard')



@section('inner-content')

<div class="py-12">

    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">



        <div class="mb-6">

            <div class="flex items-center gap-4 mb-4">

                <div class="w-1 h-9 bg-blue-600 rounded-sm flex-shrink-0"></div>

                <h2 class="text-lg font-black uppercase text-slate-900">Demandes de <span class="text-blue-600">Congés</span></h2>

            </div>



            @if(!auth()->user()->isAdmin())

                <form action="{{ route('conges.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">

                    @csrf



                    @if(session('success'))

                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>

                    @endif



                    @if($errors->any())

                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">

                            <ul class="list-disc pl-5">

                                @foreach($errors->all() as $err)

                                    <li>{{ $err }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif



                    <div class="flex flex-wrap gap-4 mb-4">

                        <div class="flex-1 min-w-[200px]">

                            <label class="block text-sm font-black text-slate-900 uppercase mb-2">Date de début</label>

                            <input type="date" name="start_date" required class="w-full p-2 rounded-md border border-slate-200 bg-white text-slate-900">

                        </div>

                        <div class="flex-1 min-w-[200px]">

                            <label class="block text-sm font-black text-slate-900 uppercase mb-2">Date de fin</label>

                            <input type="date" name="end_date" required class="w-full p-2 rounded-md border border-slate-200 bg-white text-slate-900">

                        </div>

                        <div class="flex-1 min-w-[200px]">

                            <label class="block text-sm font-black text-slate-900 uppercase mb-2">Type</label>

                            <select name="type" required class="w-full p-2 rounded-md border border-slate-200 bg-white text-slate-900">

                                <option value="">Choisir le type</option>

                                <option value="congé payé">Congé payé</option>

                                <option value="congé maladie">Congé maladie</option>

                                <option value="autre">Autre</option>

                            </select>

                        </div>

                    </div>



                    <div class="mb-4">

                        <label class="block text-sm font-black text-slate-900 uppercase mb-2">Motif (optionnel)</label>

                        <textarea name="reason" rows="4" class="w-full p-3 rounded-md border border-slate-200 bg-white text-slate-900" placeholder="Décrivez la raison de votre absence..."></textarea>

                    </div>



                    <div>

                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 text-white font-black uppercase rounded-md hover:bg-blue-700">Envoyer la demande</button>

                    </div>

                </form>

            @endif



            <div>

                <h3 class="text-xs uppercase text-slate-500 tracking-wider font-black mb-4">Historique de l'équipe</h3>



                @if(!isset($conges) || $conges->isEmpty())

                    <div class="p-6 bg-white rounded-2xl border border-slate-200/50 text-center text-slate-600 font-semibold">AUCUN CONGÉ ENREGISTRÉ POUR LE MOMENT</div>

                @else

                    <div class="space-y-3">

                        @foreach($conges as $c)

                            @php

                                $status = strtolower($c->status ?? '');

                            @endphp

                            <div class="flex items-center justify-between p-4 bg-white border border-slate-200/50 rounded-2xl">

                                <div>

                                    <div class="font-black text-slate-900 uppercase">{{ $c->user->name ?? 'Utilisateur' }}</div>

                                    <div class="text-sm text-slate-500 font-semibold">{{ $c->start_date }} → {{ $c->end_date }}</div>

                                </div>

                                @if($status === 'approuve')

                                    <div class="text-xs font-black uppercase text-emerald-600 bg-emerald-50 px-3 py-1 rounded-md">ACCEPTÉ</div>

                                @elseif($status === 'refuse')

                                    <div class="text-xs font-black uppercase text-red-600 bg-red-50 px-3 py-1 rounded-md">REFUSÉ</div>

                                @else

                                    <div class="text-xs font-black uppercase text-slate-500 bg-slate-50 px-3 py-1 rounded-md">EN ATTENTE</div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>



    </div>

</div>

@endsection
