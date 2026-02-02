<x-guest-layout>
    {{-- On remplace -mt-10 par py-10 pour donner de l'espace en haut --}}
    <div class="flex flex-col items-center justify-center py-10"> 
        
        {{-- Logo agrandi et plus contrasté --}}
        <div class="mb-8 text-center">
            {{-- W-14 H-14 pour que le "E" respire mieux --}}
            <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg shadow-blue-200 mb-4 transform -rotate-3 transition-transform hover:rotate-0">
                {{-- Text-2xl et font-black pour une visibilité maximale du E --}}
                <span class="text-white text-2xl font-black italic tracking-tighter">E</span>
            </div>
            <h2 class="text-xl font-black text-slate-800 tracking-[0.2em] uppercase">Empreinte</h2>
            <div class="h-1 w-8 bg-blue-600 mx-auto mt-2 rounded-full"></div>
        </div>

        <x-auth-session-status class="mb-6 text-[11px] font-bold text-blue-600 text-center" :status="session('status')" />

        {{-- Formulaire avec largeur maîtrisée --}}
        <form method="POST" action="{{ route('login') }}" class="w-full max-w-[320px] space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Identifiant</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none"
                    placeholder="votre@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] font-bold text-red-500" />
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center px-1">
                    <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a class="text-[9px] font-bold text-blue-600 hover:text-blue-700 uppercase" href="{{ route('password.request') }}">Oublié ?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required 
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none"
                    placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] font-bold text-red-500" />
            </div>

            <div class="flex items-center px-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer text-slate-400 hover:text-slate-600 transition-colors">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-0">
                    <span class="ms-2 text-[10px] font-bold uppercase tracking-wide">Rester connecté</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-slate-100 transition-all active:scale-95">
                    Connexion
                </button>
            </div>

            <div class="text-center pt-6 border-t border-slate-50">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    {{ __("Nouveau ?") }}
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline ms-1 font-black">Créer profil</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
          