<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Administration — Empreinte</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .active-link { background-color: #2563eb !important; color: white !important; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex h-screen bg-slate-50">
    @include('layouts.partials.sidebar')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <div class="relative w-full max-w-md">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                <input type="text" placeholder="Recherche..." class="w-full bg-slate-50 border-none rounded-xl py-2 pl-11 text-[12px] outline-none">
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right border-r pr-6 border-slate-200">
                    @if(Route::has('profile.self'))
                        <a href="{{ route('profile.self') }}" class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1 inline-block">{{ auth()->user()->name }}</a>
                    @else
                        <p class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1">{{ auth()->user()->name }}</p>
                    @endif
                    <span class="text-[8px] font-bold text-blue-600 uppercase tracking-widest">{{ auth()->user()->isAdmin() ? 'Administrateur' : 'Collaborateur' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fas fa-power-off"></i></button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 no-scrollbar bg-white">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
