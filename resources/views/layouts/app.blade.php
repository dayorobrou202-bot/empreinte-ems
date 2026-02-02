<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empreinte - EMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; overflow: hidden; height: 100vh; background-color: #6488b4; color: #ffffff; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .active-link { background-color: #2563eb !important; color: white !important; }
        [x-cloak] { display: none !important; }

        /* Utility button classes to harmonize legacy btn/btn-primary usages */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid rgba(148,163,184,0.2);
            border-radius: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .btn-primary {
            background: #2563eb;
            background-color: #2563eb;
            color: #ffffff;
            border: 1px solid transparent;
            border-radius: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
            padding: 0.6rem 1.1rem;
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="flex h-screen bg-[#6488b4]">

    @include('layouts.partials.sidebar')

    <div class="flex-1 flex flex-col min-w-0 h-screen">
        <header class="h-16 bg-white border-b border-slate-200/50 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="relative w-full max-w-xs md:max-w-md hidden sm:block">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    <input type="text" placeholder="Recherche..." class="w-full bg-slate-50/50 border-none rounded-xl py-2 pl-11 text-[12px] outline-none">
                </div>
            </div>

            <div class="flex items-center gap-4 md:gap-6">
                <div class="text-right border-r pr-4 md:pr-6 border-slate-200">
                    @if(Route::has('profile.self'))
                        <a href="{{ route('profile.self') }}" class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1 inline-block">{{ auth()->user()->name }}</a>
                    @else
                        <p class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1">{{ auth()->user()->name }}</p>
                    @endif
                    <span class="text-[8px] font-bold text-blue-600 uppercase tracking-widest">
                        {{ auth()->user()->isAdmin() ? 'Administrateur' : 'Collaborateur' }}
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-power-off"></i>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 no-scrollbar bg-[#6488b4]">
            @yield('content')
        </main>
    </div>

</body>
</html>