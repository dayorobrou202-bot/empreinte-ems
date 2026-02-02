@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-sm text-slate-400 uppercase tracking-widest']) }}>
    {{ $value ?? $slot }}
</label>
