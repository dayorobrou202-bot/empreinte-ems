@props(['id' => 'modal'])
<div id="{{ $id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4 hidden" x-cloak>
    <div class="bg-white p-6 w-full max-w-2xl border border-slate-200 shadow-2xl rounded-[20px]">
        {{ $slot }}
    </div>
</div>
