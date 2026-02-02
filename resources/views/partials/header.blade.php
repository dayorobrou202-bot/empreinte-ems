<header class="bg-white border-b p-4 flex items-center justify-between">
    <div class="text-lg font-bold">Tableau</div>
    <div class="flex items-center gap-4">
        @auth
            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-sm text-red-600">Se déconnecter</button></form>
        @endauth
    </div>
</header>
