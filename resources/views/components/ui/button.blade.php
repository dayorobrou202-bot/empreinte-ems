@props(['type' => 'button', 'variant' => 'primary'])
@php
    $base = 'inline-flex items-center justify-center px-4 py-2 font-black text-xs uppercase rounded-xl transition-all';
    $styles = $variant === 'primary' ? $base . ' bg-blue-600 text-white hover:bg-blue-700' : $base . ' bg-white text-slate-800 border border-slate-200 hover:bg-slate-50';
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => $styles]) }}>
    {{ $slot }}
</button>
