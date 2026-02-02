<nav>
    <ul class="py-2">
        <li>
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded {{ request()->routeIs('dashboard') ? 'bg-purple-50' : '' }}">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" /></svg>
                Accueil
            </a>
        </li>

        <li>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Mon profil
            </a>
        </li>

        <li>
            <a href="{{ route('presences') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Ma Présence / Pointage
            </a>
        </li>

        <li>
            <a href="{{ route('tasks.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                Mes Tâches
            </a>
        </li>

        <li>
            <a href="{{ route('conges') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Demande de Congés
            </a>
        </li>

        <li>
            @if(Auth::check() && Auth::user()->isAdmin())
                {{-- L'ADMIN VOIT L'ÉMISSION --}}
                <a href="{{ route('admin.paie') }}" class="flex items-center px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded shadow-sm mb-1">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-bold uppercase text-xs">Émettre Paie (Admin)</span>
                </a>
            @else
                {{-- L'EMPLOYÉ VOIT SES REÇUS --}}
                <a href="{{ route('paie') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded">
                    <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    Mes Bulletins de Paie
                </a>
            @endif
        </li>

        @if(Auth::check() && Auth::user()->isAdmin())
        <li>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded border-l-4 border-purple-500">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Liste du Personnel
            </a>
        </li>
        @endif

        <li>
            <a href="{{ route('messages') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900 rounded">
                <svg class="w-5 h-5 mr-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m-2 8a9 9 0 100-18 9 9 0 000 18z" /></svg>
                Messages
            </a>
        </li>

        <li class="pt-4 border-t border-gray-100 mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded font-bold">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" /></svg>
                    Déconnexion
                </button>
            </form>
        </li>
    </ul>
</nav>
