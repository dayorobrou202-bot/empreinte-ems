<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empreinte - Design</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="min-h-screen bg-[#f1f5f9] text-slate-900">

    <?php echo $__env->make('layouts.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="lg:pl-64 flex flex-col min-h-screen">
        <header class="h-16 bg-white border-b border-slate-200/50 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="relative w-full max-w-xs md:max-w-md hidden sm:block">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    <input type="text" placeholder="Recherche..." class="w-full bg-slate-50 border-none rounded-xl py-2 pl-11 text-[12px] outline-none">
                </div>
            </div>

            <div class="flex items-center gap-4 md:gap-6">
                <div class="text-right border-r pr-4 md:pr-6 border-slate-200">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Route::has('profile.self')): ?>
                            <a href="<?php echo e(route('profile.self')); ?>" class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1 inline-block"><?php echo e(auth()->user()->name); ?></a>
                        <?php else: ?>
                            <p class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1"><?php echo e(auth()->user()->name); ?></p>
                        <?php endif; ?>
                        <span class="text-[8px] font-bold text-blue-600 uppercase tracking-widest">
                            <?php echo e(auth()->user()->isAdmin() ? 'Administrateur' : 'Collaborateur'); ?>

                        </span>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="font-black text-slate-900 uppercase text-[10px] leading-none mb-1 inline-block">Se connecter</a>
                    <?php endif; ?>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-10">
            <div class="max-w-[1200px] mx-auto">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

</body>
</html><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/layouts/design.blade.php ENDPATH**/ ?>