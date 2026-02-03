

<?php $__env->startSection('inner-content'); ?>
<div class="max-w-4xl mx-auto py-6 px-4 sm:py-8">

    
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <div class="flex items-center gap-3">
            <div style="width: 4px; height: 32px; background-color: #2563eb; border-radius: 2px;"></div>
            <h1 class="text-slate-900 font-black uppercase tracking-widest text-lg sm:text-xl m-0">MES MISSIONS</h1>
        </div>
        <div class="hidden sm:block px-4 py-1.5 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase">
            STATUT : TEMPS RÉEL
        </div>
    </div>

    
    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $color = '#cbd5e1'; 
                if($task->status === 'en cours') {
                    $color = $task->is_overdue ? '#ef4444' : '#2563eb';
                } elseif($task->status === 'terminé') {
                    $color = '#10b981';
                }
            ?>

            
            <div class="bg-white rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between shadow-sm relative overflow-hidden border border-slate-200" 
                 style="border-left: 8px solid <?php echo e($color); ?> !important;">
                
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-slate-900 font-black uppercase text-sm tracking-tight break-words"><?php echo e($task->title); ?></span>
                        <?php if($task->status === 'en cours' && $task->is_overdue): ?>
                             <span class="animate-pulse bg-rose-100 text-rose-600 text-[8px] font-black px-2 py-0.5 rounded-full uppercase">Retard</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-slate-400 text-[11px] mb-4 uppercase font-bold leading-relaxed">
                        <?php echo e($task->description ?? 'Aucune consigne particulière'); ?>

                    </div>
                    
                    
                    <div class="grid grid-cols-2 sm:flex sm:items-center gap-4 sm:gap-8 pt-3 border-t border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5">Assignée</span>
                            <span class="text-[10px] sm:text-[11px] font-black text-slate-600"><?php echo e($task->created_at->format('d/m/Y')); ?></span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5">Échéance</span>
                            <span class="text-[10px] sm:text-[11px] font-black <?php echo e($task->is_overdue ? 'text-rose-600' : 'text-blue-600'); ?>">
                                <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d/m/Y')); ?>

                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="mt-5 sm:mt-0 sm:ml-6 flex justify-end">
                    <div class="w-full sm:w-auto min-w-[140px]">
                        <?php if($task->status === 'terminé'): ?>
                            <div class="text-center sm:text-right">
                                <span class="inline-block px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase rounded-lg">Terminée</span>
                            </div>
                        <?php elseif($task->status === 'échoué'): ?>
                            <div class="text-center sm:text-right">
                                <span class="inline-block px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-black uppercase rounded-lg">Échec</span>
                            </div>
                        <?php else: ?>
                            <form action="<?php echo e(route('tasks.confirm', $task->id)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 text-[10px] font-black uppercase rounded-xl text-white transition-all shadow-md active:scale-95"
                                    style="background-color: <?php echo e($color); ?>; border: none;">
                                    <?php echo e($task->is_overdue ? 'Confirmer l\'échec' : 'Marquer comme fini'); ?>

                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 text-center">
                <p class="text-slate-300 font-black uppercase text-xs tracking-widest">Aucune mission pour le moment</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-pulse {
        animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/tasks/index.blade.php ENDPATH**/ ?>