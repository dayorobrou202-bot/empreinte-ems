

<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-[#3b82f6] -m-8 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-black text-white uppercase tracking-tighter mb-6">Gestion des Utilisateurs</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <h2 class="text-sm font-black text-slate-800 uppercase mb-6">Nouveau Stagiaire</h2>
                
                <form action="<?php echo e(route('users.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nom</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border-none rounded-xl p-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 outline-none" placeholder="Nom complet">
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border-none rounded-xl p-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 outline-none" placeholder="email@exemple.com">
                    </div>

                    
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mot de passe</label>
                        <input type="password" name="password" required class="w-full bg-slate-50 border-none rounded-xl p-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 outline-none" placeholder="••••••••">
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigner Mentor</label>
                        <select name="mentor_id" required class="w-full bg-slate-50 border-none rounded-xl p-3 text-sm font-bold text-slate-700 outline-none cursor-pointer">
                            <option value="" disabled selected>Choisir...</option>
                            <?php $__currentLoopData = $collaborateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($collab->id); ?>"><?php echo e($collab->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-[#3b82f6] hover:bg-blue-700 text-white font-black py-4 rounded-xl uppercase text-[10px] tracking-widest transition-all">
                        Enregistrer
                    </button>
                </form>
            </div>

            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <h2 class="text-sm font-black text-slate-800 uppercase mb-6">Stagiaires</h2>
                    <?php if(session('success')): ?>
                        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700">
                            <?php echo e(session('success')); ?>

                            <?php if(session('temp_password')): ?>
                                <div class="mt-2 text-sm font-bold">Mot de passe temporaire : <span class="text-slate-900"><?php echo e(session('temp_password')); ?></span></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $users->where('position', 'Stagiaire'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stagiaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center font-black text-[#3b82f6] shadow-sm border border-slate-100">
                                <?php echo e(substr($stagiaire->name, 0, 1)); ?>

                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-800"><?php echo e($stagiaire->name); ?></h3>
                                <p class="text-[10px] text-blue-500 font-bold uppercase">Mentor: <?php echo e($stagiaire->mentor->name ?? 'Aucun'); ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <?php if(auth()->user() && auth()->user()->role_id == 1 && \Illuminate\Support\Facades\Route::has('admin.users.reset_password')): ?>
                                <form action="<?php echo e(route('admin.users.reset_password', $stagiaire->id)); ?>" method="POST" onsubmit="return confirm('Réinitialiser le mot de passe ?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-slate-500 hover:text-slate-700 transition-colors text-xs px-2 py-1 border rounded">Réinitialiser MDp</button>
                                </form>
                            <?php endif; ?>

                            <form action="<?php echo e(route('users.destroy', $stagiaire->id)); ?>" method="POST" onsubmit="return confirm('Supprimer ?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/users/index.blade.php ENDPATH**/ ?>