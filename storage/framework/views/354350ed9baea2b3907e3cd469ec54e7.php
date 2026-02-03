

<?php $__env->startSection('inner-content'); ?>
<div class="space-y-6 min-h-screen" style="font-family: 'Inter', sans-serif; padding: 10px;">
    
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Gestion des <span class="text-blue-600">Missions</span>
            </h1>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Attribuer et suivre les objectifs</p>
        </div>
    </div>

    
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        <h2 style="margin:0 0 20px 0; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:1px; color:#0f172a">
            Nouvelle mission
        </h2>
        
        <form action="<?php echo e(route('admin.tasks.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Collaborateur</label>
                    <select name="user_id" required style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; color:#0f172a; font-weight:600; font-size:11px; outline:none; width:100%;">
                        <option value="">Sélectionner...</option>
                        <?php $__currentLoopData = $collaborators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($collab->id); ?>"><?php echo e($collab->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex flex-col gap-2 md:col-span-1">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Intitulé</label>
                    <input type="text" name="title" required placeholder="Titre de la mission" 
                        style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; color:#0f172a; font-weight:500; font-size:12px; outline:none; width:100%;">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Échéance</label>
                    <input type="date" name="due_date" required value="<?php echo e(date('Y-m-d')); ?>"
                        style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; color:#0f172a; font-weight:600; font-size:11px; outline:none; width:100%;">
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-2">
                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Instructions</label>
                <textarea name="description" rows="2" placeholder="Détails de la mission..." 
                    style="width:100%; background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; outline:none; font-weight:400; font-size:12px; resize:none;"></textarea>
            </div>

            <button type="submit" style="margin-top:20px; background:#2563eb; color:#ffffff; padding:14px; border-radius:12px; border:none; font-weight:900; text-transform:uppercase; cursor:pointer; display:block; width:100%; font-size:11px; letter-spacing:1px;">
                Assigner la mission
            </button>
        </form>
    </div>

    
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        <div style="background:#f8fafc; padding:15px 25px; border-bottom:1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <span style="color:#64748b; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:1px;">Suivi des objectifs actifs</span>
            <span style="font-size: 8px; font-weight: 800; color: #94a3b8; background: #ffffff; padding: 4px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">HISTORIQUE : 5 JOURS</span>
        </div>

        <div class="hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="background: #ffffff;">
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Collaborateur & Mission</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Cycle de vie</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Calcul de la couleur de la bordure gauche
                        $borderLeftColor = '#e2e8f0'; // Gris par défaut
                        if($task->status === 'en cours') {
                            $borderLeftColor = $task->is_overdue ? '#ef4444' : '#2563eb'; // Rouge si retard, Bleu sinon
                        } elseif($task->status === 'terminé') {
                            $borderLeftColor = '#10b981'; // Vert
                        }
                    ?>
                    <tr class="hover:bg-slate-50/50 transition-colors" style="border-left: 6px solid <?php echo e($borderLeftColor); ?>;">
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-[10px] border border-slate-200 uppercase">
                                    <?php echo e(substr($task->user->name, 0, 2)); ?>

                                </div>
                                <div>
                                    <div class="font-black text-slate-900 text-[13px] uppercase tracking-tight"><?php echo e($task->title); ?></div>
                                    <div class="text-[9px] font-black text-blue-600 uppercase tracking-tighter">Assigné à : <?php echo e($task->user->name); ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="p-5 text-center">
                            <div class="flex items-center justify-center gap-8">
                                <div class="text-center">
                                    <div class="text-[8px] font-black text-slate-300 uppercase mb-1">Date début</div>
                                    <div class="text-[11px] font-bold text-slate-600"><?php echo e($task->created_at->format('d/m/Y')); ?></div>
                                </div>
                                <div class="text-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="text-[8px] font-black <?php echo e($task->is_overdue ? 'text-rose-400' : 'text-slate-300'); ?> uppercase mb-1">Échéance</div>
                                    <div class="text-[11px] font-black <?php echo e($task->is_overdue ? 'text-rose-600' : 'text-slate-800'); ?>">
                                        <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d/m/Y')); ?>

                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="p-5 text-right">
                            <?php
                                $statusStyle = [
                                    'terminé' => 'text-emerald-600 border-emerald-100 bg-emerald-50',
                                    'échoué' => 'text-rose-600 border-rose-100 bg-rose-50',
                                    'en cours' => 'text-blue-600 border-blue-100 bg-blue-50 animate-pulse'
                                ];
                                $currentStyle = $statusStyle[$task->status] ?? 'text-slate-400 border-slate-100 bg-slate-50';
                            ?>
                            <span class="px-4 py-2 border rounded-xl text-[9px] font-black uppercase tracking-widest <?php echo e($currentStyle); ?>">
                                <?php echo e($task->status); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="p-16 text-center text-slate-300 text-[11px] font-black uppercase tracking-[0.2em]">Aucun objectif en cours</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="md:hidden divide-y divide-slate-100">
            <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $borderColorMob = ($task->is_overdue && $task->status === 'en cours') ? '#ef4444' : '#2563eb';
                if($task->status === 'terminé') $borderColorMob = '#10b981';
            ?>
            <div class="p-5" style="border-left: 6px solid <?php echo e($borderColorMob); ?>;">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-[12px] font-black text-slate-900 uppercase"><?php echo e($task->title); ?></div>
                        <div class="text-[9px] font-bold text-blue-600 uppercase">Pour : <?php echo e($task->user->name); ?></div>
                    </div>
                    <span class="px-2 py-1 border rounded-lg text-[8px] font-black uppercase <?php echo e($task->status === 'terminé' ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-slate-400 border-slate-100 bg-slate-50'); ?>">
                        <?php echo e($task->status); ?>

                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <div>
                        <div class="text-[8px] font-black text-slate-400 uppercase">Lancée le</div>
                        <div class="text-[10px] font-bold text-slate-700"><?php echo e($task->created_at->format('d/m/y')); ?></div>
                    </div>
                    <div class="text-right">
                        <div class="text-[8px] font-black text-slate-400 uppercase">Date butoir</div>
                        <div class="text-[10px] font-black <?php echo e($task->is_overdue ? 'text-rose-600' : 'text-slate-900'); ?>">
                            <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d/m/y')); ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-10 text-center text-slate-300 text-[10px] font-black uppercase">Zéro mission</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .animate-pulse {
        animation: pulse-soft 2s infinite;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/admin/tasks/index.blade.php ENDPATH**/ ?>