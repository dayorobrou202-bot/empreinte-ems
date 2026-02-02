<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false" 
     class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden">
</div>

<aside 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-slate-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-2xl flex-shrink-0 flex flex-col h-screen"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <div class="p-6 flex flex-col h-full overflow-hidden">
        
        <div class="flex items-center gap-3 mb-10 flex-shrink-0">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-xl text-white shadow-lg italic">E</div>
            <div class="leading-none">
                <h1 class="font-black text-slate-900 text-lg uppercase tracking-tighter">Empreinte</h1>
                <p class="text-[8px] text-slate-500 font-bold uppercase tracking-widest mt-1">Management System</p>
            </div>
            
            <button @click="sidebarOpen = false" class="ml-auto p-2 text-slate-600 hover:text-slate-900 lg:hidden">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto no-scrollbar">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Navigation</p>
            
            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('dashboard') ? 'active-link shadow-lg shadow-blue-100' : 'text-slate-600 hover:bg-slate-50'); ?>">
                <i class="fas fa-th-large w-5 text-center text-sm"></i>
                <span class="font-bold text-[11px] uppercase tracking-wider">Dashboard</span>
            </a>

            <a href="<?php echo e(route('presences')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('presences*') ? 'active-link' : 'text-slate-600 hover:bg-slate-50'); ?>">
                <i class="fas fa-fingerprint w-5 text-center text-sm"></i>
                <span class="font-bold text-[11px] uppercase tracking-wider">Présences</span>
            </a>

            <a href="<?php echo e(route('tasks.index')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('tasks*') ? 'active-link' : 'text-slate-600 hover:bg-slate-50'); ?>">
                <i class="fas fa-tasks w-5 text-center text-sm"></i>
                <span class="font-bold text-[11px] uppercase tracking-wider">Tâches</span>
            </a>

            <a href="<?php echo e(route('conges')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('conges*') ? 'active-link' : 'text-slate-600 hover:bg-slate-50'); ?>">
                <i class="fas fa-calendar-alt w-5 text-center text-sm"></i>
                <span class="font-bold text-[11px] uppercase tracking-wider">Congés</span>
            </a>

            <a href="<?php echo e(route('paie')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('paie*') ? 'active-link' : 'text-slate-600 hover:bg-slate-50'); ?>">
                <i class="fas fa-file-invoice-dollar w-5 text-center text-sm"></i>
                <span class="font-bold text-[11px] uppercase tracking-wider">Ma Paie</span>
            </a>

            <a href="<?php echo e(route('messages')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('messages*') ? 'active-link' : 'text-slate-600 hover:bg-slate-50'); ?>">
                <i class="fas fa-comments w-5 text-center text-sm"></i>
                <span class="font-bold text-[11px] uppercase tracking-wider">Messages</span>
            </a>

            
            <?php if(auth()->user()->isAdmin() || auth()->user()->role_id == 1): ?>
                <div class="pt-4 mt-4 border-t border-slate-100">
                    <p class="px-4 text-[9px] font-black text-blue-600 uppercase tracking-[0.2em] mb-3">Administration</p>
                    
                    <a href="<?php echo e(route('users.index')); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all <?php echo e(request()->routeIs('users*') ? 'active-link shadow-lg shadow-blue-100' : 'text-slate-600 hover:bg-slate-50'); ?>">
                        <i class="fas fa-user-shield w-5 text-center text-sm"></i>
                        <span class="font-bold text-[11px] uppercase tracking-wider">Utilisateurs</span>
                    </a>
                </div>
            <?php endif; ?>

            
            <div x-data="{ open: <?php echo e(request()->is('documents*') ? 'true' : 'false'); ?> }" class="pt-2">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-folder w-5 text-center text-sm"></i>
                        <span class="font-bold text-[11px] uppercase">Documents</span>
                    </div>
                    <i class="fas fa-chevron-down text-[9px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="open" x-cloak x-transition class="mt-1 ml-9 border-l border-slate-200 space-y-1">
                    <a href="<?php echo e(route('documents.recus')); ?>" class="block px-4 py-1.5 text-[10px] font-bold uppercase <?php echo e(request()->routeIs('documents.recus') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900'); ?>">
                        Reçus
                    </a>
                    <a href="<?php echo e(route('documents.envoyes')); ?>" class="block px-4 py-1.5 text-[10px] font-bold uppercase <?php echo e(request()->routeIs('documents.envoyes') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900'); ?>">
                        Envoyés
                    </a>
                </div>
            </div>

            <div class="pt-6 mt-4 border-t border-slate-200">
                <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </div>
                    <span class="text-[9px] font-black text-green-600 uppercase tracking-widest">Connecté</span>
                </div>
            </div>
        </nav>
    </div>
</aside><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>