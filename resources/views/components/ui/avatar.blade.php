@props(['name' => null, 'size' => 'md'])
@php
    $initial = $name ? strtoupper(substr($name,0,1)) : '?';
    $sizes = ['sm' => 'w-10 h-10 text-lg', 'md' => 'w-16 h-16 text-2xl', 'lg' => 'w-24 h-24 text-4xl'];
    $cls = $sizes[$size] ?? $sizes['md'];
@endphp
<div {{ $attributes->merge(['class' => "bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-black rounded-[20px] $cls"]) }}>
    {{ $initial }}
</div>
