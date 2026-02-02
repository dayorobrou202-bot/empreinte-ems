

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
        <div style="background:#f8fafc; padding:15px 25px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:10px; font-weight:900; text-transform:uppercase;">
            Suivi des missions
        </div>

        <div class="hidden md:block">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-5">
                            <div class="font-semibold text-slate-800 text-[14px]"><?php echo e($task->title); ?></div>
                            <div class="text-[11px] text-slate-400 mt-1"><?php echo e(Str::limit($task->description, 50)); ?></div>
                        </td>
                        <td class="p-5 text-center">
                            <div class="text-[10px] font-bold text-blue-600 uppercase">Pour : <?php echo e($task->user->name); ?></div>
                        </td>
                        <td class="p-5 text-right">
                            <div class="text-[11px] font-bold text-slate-700 mb-1"><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d/m/Y')); ?></div>
                            <span class="px-3 py-1 border rounded-lg text-[9px] font-black <?php echo e($task->status === 'terminé' ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-slate-400 border-slate-100 bg-slate-50'); ?>">
                                <?php echo e(strtoupper($task->status)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="p-10 text-center text-slate-400 text-[11px] font-bold uppercase">Aucune mission</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-slate-100">
            <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-5 space-y-3">
                <div class="flex justify-between items-start">
                    <div class="font-semibold text-slate-900 text-sm leading-tight"><?php echo e($task->title); ?></div>
                    <span class="flex-shrink-0 px-2 py-1 border rounded-md text-[8px] font-black <?php echo e($task->status === 'terminé' ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-slate-400 border-slate-100 bg-slate-50'); ?>">
                        <?php echo e(strtoupper($task->status)); ?>

                    </span>
                </div>
                <div class="text-[11px] text-slate-500 leading-relaxed"><?php echo e($task->description); ?></div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-50">
                    <div class="text-[10px] font-bold text-slate-900"><?php echo e($task->user->name); ?></div>
                    <div class="text-[10px] font-black text-blue-600"><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d/m/y')); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-10 text-center text-slate-400 text-[10px] font-bold uppercase">Aucune mission</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/admin/tasks/index.blade.php ENDPATH**/ ?>