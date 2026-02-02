

<?php $__env->startSection('inner-content'); ?>
<div class="space-y-6 min-h-screen" style="font-family: 'Inter', sans-serif; padding: 10px;">
    
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Historique <span class="text-blue-600">Présences</span>
            </h1>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Flux de présence administratif</p>
        </div>
        <a href="<?php echo e(route('admin.presences.export', request()->query())); ?>" 
           class="w-full sm:w-auto text-center bg-white text-slate-900 px-6 py-2.5 font-bold text-[10px] uppercase border border-slate-300 rounded-xl shadow-sm hover:bg-slate-50 transition-all">
            Exporter CSV
        </a>
    </div>

    
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        <form method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-4 md:gap-8">
            <div class="flex flex-col gap-2 flex-1">
                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Collaborateur</label>
                <select name="user_id" style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; color:#0f172a; font-weight:600; font-size:11px; outline:none; width:100%;">
                    <option value="">Tous les membres</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php echo e(request()->query('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Date</label>
                <input type="date" name="date" value="<?php echo e($filterDate ?? date('Y-m-d')); ?>" 
                    style="background:#f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; color:#0f172a; font-weight:600; font-size:11px; outline:none; width:100%;">
            </div>

            <div class="flex items-center gap-4 md:mt-5">
                <button type="submit" class="flex-1 md:flex-none text-blue-600 font-bold text-[10px] uppercase tracking-widest">Filtrer</button>
                <a href="<?php echo e(route('admin.presences.index')); ?>" class="flex-1 md:flex-none text-slate-400 font-bold text-[10px] uppercase tracking-widest text-center">Reset</a>
            </div>
        </form>
    </div>

    
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase">Collaborateur</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Matin</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Midi</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Soir</th>
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50"> 
                    <?php $__currentLoopData = $presenceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-5 flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-xs rounded-xl border border-slate-200">
                                    <?php echo e(strtoupper(substr($row->user->name, 0, 1))); ?>

                                </div>
                                <div>
                                    <div class="text-[14px] font-semibold text-slate-800"><?php echo e($row->user->name); ?></div>
                                    <div class="text-[9px] text-slate-400 font-medium uppercase">ID: <?php echo e($row->user->id); ?></div>
                                </div>
                            </td>
                            
                            <td class="p-5 text-center text-[11px] font-bold text-slate-700">
                                <?php echo e($row->heure_matin ?? '--:--'); ?>

                            </td>
                            <td class="p-5 text-center text-[11px] font-bold text-slate-700">
                                <?php echo e($row->heure_midi ?? '--:--'); ?>

                            </td>
                            <td class="p-5 text-center text-[11px] font-bold text-slate-700">
                                <?php echo e($row->heure_soir ?? '--:--'); ?>

                            </td>
                            <td class="p-5 text-right">
                                <span class="px-3 py-1 border rounded-lg text-[9px] font-bold <?php echo e($row->present ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50'); ?>">
                                    <?php echo e($row->present ? 'PRÉSENT' : 'ABSENT'); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="md:hidden divide-y divide-slate-100">
            <?php $__currentLoopData = $presenceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-[10px] rounded-lg border border-slate-200">
                                <?php echo e(strtoupper(substr($row->user->name, 0, 1))); ?>

                            </div>
                            <div class="font-semibold text-slate-800 text-sm"><?php echo e($row->user->name); ?></div>
                        </div>
                        <span class="px-2 py-1 border rounded-md text-[8px] font-bold <?php echo e($row->present ? 'text-emerald-600 border-emerald-100 bg-emerald-50' : 'text-rose-600 border-rose-100 bg-rose-50'); ?>">
                            <?php echo e($row->present ? 'PRÉSENT' : 'ABSENT'); ?>

                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3 rounded-xl text-center">
                        <div>
                            <div class="text-[8px] text-slate-400 uppercase font-bold">Matin</div>
                            <div class="text-[11px] font-bold text-slate-700"><?php echo e($row->heure_matin ?? '--:--'); ?></div>
                        </div>
                        <div>
                            <div class="text-[8px] text-slate-400 uppercase font-bold">Midi</div>
                            <div class="text-[11px] font-bold text-slate-700"><?php echo e($row->heure_midi ?? '--:--'); ?></div>
                        </div>
                        <div>
                            <div class="text-[8px] text-slate-400 uppercase font-bold">Soir</div>
                            <div class="text-[11px] font-bold text-slate-700"><?php echo e($row->heure_soir ?? '--:--'); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/presences/admin_index.blade.php ENDPATH**/ ?>