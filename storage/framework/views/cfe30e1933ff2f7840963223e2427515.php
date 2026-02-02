<?php $__env->startSection('inner-content'); ?>
<div class="min-h-screen py-4 md:py-6 space-y-8 px-4 md:px-0 max-w-5xl mx-auto">
    
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
            $stats = [
                ['Collaborateurs', $collaborateursCount ?? '7', 'fa-users', 'text-blue-500'],
                ['Moyenne', ($avgScore ?? '89').'%', 'fa-chart-line', 'text-emerald-500'],
                ['Évaluations', $evaluationsCount ?? '0', 'fa-star', 'text-amber-500'],
                ['Présences', $presenceCount ?? '1', 'fa-calendar-check', 'text-indigo-500']
            ];
        ?>

        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white p-5 border border-slate-200/50 shadow-sm rounded-2xl flex items-center justify-between group">
            <div class="space-y-1">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?php echo e($stat[0]); ?></p>
                <h4 class="text-2xl font-black text-slate-900 leading-none"><?php echo e($stat[1]); ?></h4>
            </div>
            <div class="w-10 h-10 bg-slate-50 flex items-center justify-center rounded-xl border border-slate-100">
                <i class="fas <?php echo e($stat[2]); ?> <?php echo e($stat[3]); ?> text-lg"></i>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-slate-900 font-black text-lg uppercase tracking-widest">CLASSEMENT ÉQUIPE</h2>
            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-[9px] font-black uppercase">Live</span>
        </div>

        <div class="space-y-3">
            <?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $score = round($user->avgScore ?? $user->avg_score ?? 0);
                    $isMe = auth()->id() && auth()->id() === ($user->id ?? null);
                    $userPercent = $user->avgScore ?? $user->avg_score ?? $score;
                ?>

                <div class="group flex flex-col md:flex-row md:items-center justify-between p-4 bg-white border <?php echo e($isMe ? 'border-blue-500 shadow-blue-50 shadow-md ring-1 ring-blue-500/20' : 'border-slate-200 shadow-sm'); ?> rounded-2xl transition-all hover:border-blue-300">
                    
                    <div class="flex items-center gap-4 flex-1">
                        
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-slate-50 rounded-full border border-slate-100">
                            <?php if($index == 0): ?> <span class="text-xl">🥇</span>
                            <?php elseif($index == 1): ?> <span class="text-xl">🥈</span>
                            <?php elseif($index == 2): ?> <span class="text-xl">🥉</span>
                            <?php else: ?> <span class="text-xs font-black text-slate-400">#<?php echo e($index + 1); ?></span>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('collaborator.show', $user)); ?>" class="text-sm font-black uppercase tracking-tight text-slate-900 hover:text-blue-600 truncate">
                                    <?php echo e($user->name); ?>

                                </a>
                                <?php if($isMe): ?> 
                                    <span class="text-[8px] bg-blue-600 text-white px-2 py-0.5 rounded-full font-black uppercase">Moi</span> 
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="mt-2 flex items-center gap-3">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: <?php echo e($userPercent); ?>%"></div>
                                </div>
                                <span class="text-xs font-black text-blue-600 w-8"><?php echo e($score); ?>%</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-3 md:mt-0 md:ml-6 pl-14 md:pl-0 border-t md:border-t-0 border-slate-50 pt-2 md:pt-0">
                        <span class="text-[10px] font-bold text-slate-400 uppercase bg-slate-50 px-3 py-1 rounded-lg">
                            <?php echo e($user->position ?? 'Collaborateur'); ?>

                        </span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/dashboard.blade.php ENDPATH**/ ?>