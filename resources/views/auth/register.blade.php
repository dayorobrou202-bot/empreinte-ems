<x-guest-layout>
    <div class="flex flex-col items-center justify-center py-10">
        
        {{-- Logo Empreinte --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg shadow-blue-200 mb-4 transform -rotate-3">
                <span class="text-white text-2xl font-black italic tracking-tighter">E</span>
            </div>
            <h2 class="text-xl font-black text-slate-800 tracking-[0.2em] uppercase">Inscription</h2>
            <div class="h-1 w-8 bg-blue-600 mx-auto mt-2 rounded-full"></div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="w-full max-w-[320px] space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nom Complet</label>
                <x-text-input id="name" class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none" 
                    type="text" name="name" :value="old('name')" required autofocus placeholder="Ex: Jean Dupont" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-[10px] font-bold text-red-500" />
            </div>

            <div class="space-y-1.5">
                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Adresse Email</label>
                <x-text-input id="email" class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none" 
                    type="email" name="email" :value="old('email')" required placeholder="email@entreprise.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] font-bold text-red-500" />
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Mot de passe</label>
                <x-text-input id="password" class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none"
                    type="password" name="password" required placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] font-bold text-red-500" />
            </div>

            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Confirmation</label>
                <x-text-input id="password_confirmation" class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none"
                    type="password" name="password_confirmation" required placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[10px] font-bold text-red-500" />
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-slate-100 transition-all active:scale-95">
                    Créer mon profil
                </button>
            </div>

            {{-- Lien de retour --}}
            <div class="text-center pt-6 border-t border-slate-50">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    {{ __("Déjà inscrit ?") }}
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline ms-1 font-black">Se connecter</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
