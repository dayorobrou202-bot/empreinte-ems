@extends('layouts.app')

@section('content')
<div class="managex-dashboard max-w-7xl mx-auto w-full px-6 lg:px-8 py-8 overflow-hidden">
    <div class="space-y-6">
        @yield('inner-content')
    </div>
</div>
@endsection
