@extends('layouts.dashboard')



@section('title', 'Messagerie')



@section('inner-content')

<div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/50">

    <h2 class="text-lg font-black uppercase text-slate-900 tracking-wider mb-4">Conversations</h2>



    <div class="space-y-3">

        <div class="p-4 rounded-2xl bg-white border border-slate-200/50">

            <p class="font-black text-slate-900 truncate">Jean Dupont</p>

            <p class="text-sm text-slate-400 break-words uppercase">Bonjour, comment avances-tu ?</p>

        </div>

    </div>

</div>

@endsection

