

<?php $__env->startSection('inner-content'); ?>
<div class="space-y-6 min-h-screen" style="font-family: 'Inter', sans-serif; padding: 10px;">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Gestion des <span class="text-blue-600">Congés</span>
            </h1>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Valider ou refuser les demandes d'absence</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl border border-blue-100 font-black text-[10px] uppercase shadow-sm">
                <?php echo e(count($conges)); ?> Demandes
            </div>
        </div>
    </div>

    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase">Collaborateur</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase">Type / Motif</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Dates</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__currentLoopData = $conges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-xs rounded-xl border border-slate-200">
                                    <?php echo e(strtoupper(substr($c->user->name, 0, 1))); ?>

                                </div>
                                <div class="text-[14px] font-semibold text-slate-800"><?php echo e($c->user->name); ?></div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-tighter bg-slate-100 px-2 py-1 rounded-md">
                                <?php echo e($c->type); ?>

                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <div class="text-[11px] font-bold text-slate-900"><?php echo e(\Carbon\Carbon::parse($c->start_date)->format('d/m/Y')); ?></div>
                            <div class="text-[9px] text-slate-400 font-bold">AU <?php echo e(\Carbon\Carbon::parse($c->end_date)->format('d/m/Y')); ?></div>
                        </td>
                        <td class="p-5 text-right">
                            <?php if($c->status === 'en_attente'): ?>
                                <div class="flex justify-end gap-2">
                                    <form action="<?php echo e(route('admin.conges.approve', $c->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="bg-emerald-500 text-white p-2 rounded-lg hover:bg-emerald-600 transition-all"><i class="fas fa-check text-xs"></i></button>
                                    </form>
                                    <form action="<?php echo e(route('admin.conges.reject', $c->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="bg-rose-500 text-white p-2 rounded-lg hover:bg-rose-600 transition-all"><i class="fas fa-times text-xs"></i></button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="px-3 py-1 border rounded-lg text-[9px] font-black <?php echo e($c->status == 'approuve' ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50'); ?>">
                                    <?php echo e(strtoupper($c->status)); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-slate-100">
            <?php $__currentLoopData = $conges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-5 space-y-4">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-blue-600 font-bold rounded-xl border border-slate-200">
                            <?php echo e(strtoupper(substr($c->user->name, 0, 1))); ?>

                        </div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm"><?php echo e($c->user->name); ?></div>
                            <div class="text-[10px] text-blue-600 font-bold uppercase"><?php echo e($c->type); ?></div>
                        </div>
                    </div>
                    <?php if($c->status !== 'en_attente'): ?>
                        <span class="px-2 py-1 border rounded-md text-[8px] font-black <?php echo e($c->status == 'approuve' ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50'); ?>">
                            <?php echo e(strtoupper($c->status)); ?>

                        </span>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl">
                    <div class="text-center flex-1 border-r border-slate-200">
                        <div class="text-[8px] text-slate-400 uppercase font-bold">Départ</div>
                        <div class="text-[11px] font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($c->start_date)->format('d/m/y')); ?></div>
                    </div>
                    <div class="text-center flex-1">
                        <div class="text-[8px] text-slate-400 uppercase font-bold">Retour</div>
                        <div class="text-[11px] font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($c->end_date)->format('d/m/y')); ?></div>
                    </div>
                </div>

                <?php if($c->status === 'en_attente'): ?>
                <div class="flex gap-2">
                    <form action="<?php echo e(route('admin.conges.approve', $c->id)); ?>" method="POST" class="flex-1">
                        <?php echo csrf_field(); ?>
                        <button class="w-full bg-emerald-500 text-white py-3 rounded-xl font-black text-[10px] uppercase shadow-sm">Valider</button>
                    </form>
                    <form action="<?php echo e(route('admin.conges.reject', $c->id)); ?>" method="POST" class="flex-1">
                        <?php echo csrf_field(); ?>
                        <button class="w-full bg-rose-500 text-white py-3 rounded-xl font-black text-[10px] uppercase shadow-sm">Refuser</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/pages/admin/conges_equipe.blade.php ENDPATH**/ ?>