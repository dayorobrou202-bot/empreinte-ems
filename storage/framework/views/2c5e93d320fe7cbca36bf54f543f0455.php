



<?php $__env->startSection('inner-content'); ?>

<div class="py-12">

    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">



        <div class="mb-6">

            <div class="flex items-center gap-4 mb-4">

                <div class="w-1 h-9 bg-blue-600 rounded-sm flex-shrink-0"></div>

                <h2 class="text-lg font-black uppercase text-slate-900">Demandes de <span class="text-blue-600">Congés</span></h2>

            </div>



            <?php if(!auth()->user()->isAdmin()): ?>

                <form action="<?php echo e(route('conges.store')); ?>" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">

                    <?php echo csrf_field(); ?>



                    <?php if(session('success')): ?>

                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?php echo e(session('success')); ?></div>

                    <?php endif; ?>



                    <?php if($errors->any()): ?>

                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">

                            <ul class="list-disc pl-5">

                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <li><?php echo e($err); ?></li>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </ul>

                        </div>

                    <?php endif; ?>



                    <div class="flex flex-wrap gap-4 mb-4">

                        <div class="flex-1 min-w-[200px]">

                            <label class="block text-sm font-black text-slate-900 uppercase mb-2">Date de début</label>

                            <input type="date" name="start_date" required class="w-full p-2 rounded-md border border-slate-200 bg-white text-slate-900">

                        </div>

                        <div class="flex-1 min-w-[200px]">

                            <label class="block text-sm font-black text-slate-900 uppercase mb-2">Date de fin</label>

                            <input type="date" name="end_date" required class="w-full p-2 rounded-md border border-slate-200 bg-white text-slate-900">

                        </div>

                        <div class="flex-1 min-w-[200px]">

                            <label class="block text-sm font-black text-slate-900 uppercase mb-2">Type</label>

                            <select name="type" required class="w-full p-2 rounded-md border border-slate-200 bg-white text-slate-900">

                                <option value="">Choisir le type</option>

                                <option value="congé payé">Congé payé</option>

                                <option value="congé maladie">Congé maladie</option>

                                <option value="autre">Autre</option>

                            </select>

                        </div>

                    </div>



                    <div class="mb-4">

                        <label class="block text-sm font-black text-slate-900 uppercase mb-2">Motif (optionnel)</label>

                        <textarea name="reason" rows="4" class="w-full p-3 rounded-md border border-slate-200 bg-white text-slate-900" placeholder="Décrivez la raison de votre absence..."></textarea>

                    </div>



                    <div>

                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 text-white font-black uppercase rounded-md hover:bg-blue-700">Envoyer la demande</button>

                    </div>

                </form>

            <?php endif; ?>



            <div>

                <h3 class="text-xs uppercase text-slate-500 tracking-wider font-black mb-4">Historique de l'équipe</h3>



                <?php if(!isset($conges) || $conges->isEmpty()): ?>

                    <div class="p-6 bg-white rounded-2xl border border-slate-200/50 text-center text-slate-600 font-semibold">AUCUN CONGÉ ENREGISTRÉ POUR LE MOMENT</div>

                <?php else: ?>

                    <div class="space-y-3">

                        <?php $__currentLoopData = $conges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php

                                $status = strtolower($c->status ?? '');

                            ?>

                            <div class="flex items-center justify-between p-4 bg-white border border-slate-200/50 rounded-2xl">

                                <div>

                                    <div class="font-black text-slate-900 uppercase"><?php echo e($c->user->name ?? 'Utilisateur'); ?></div>

                                    <div class="text-sm text-slate-500 font-semibold"><?php echo e($c->start_date); ?> → <?php echo e($c->end_date); ?></div>

                                </div>

                                <?php if($status === 'approuve'): ?>

                                    <div class="text-xs font-black uppercase text-emerald-600 bg-emerald-50 px-3 py-1 rounded-md">ACCEPTÉ</div>

                                <?php elseif($status === 'refuse'): ?>

                                    <div class="text-xs font-black uppercase text-red-600 bg-red-50 px-3 py-1 rounded-md">REFUSÉ</div>

                                <?php else: ?>

                                    <div class="text-xs font-black uppercase text-slate-500 bg-slate-50 px-3 py-1 rounded-md">EN ATTENTE</div>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>



    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/pages/conges.blade.php ENDPATH**/ ?>