

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-[#6488b4] py-8 px-4">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-200/50">
            <div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tighter">
                    Gestion des <span class="text-blue-600">Paiements</span>
                </h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">
                    Espace administration pour l'envoi des bulletins
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-xl">
                <i class="fas fa-file-invoice-dollar text-blue-600 text-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
            
            <?php if(session('success')): ?>
                <div class="m-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-bold text-xs uppercase tracking-widest">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.paie.store')); ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Collaborateur destinataire</label>
                        <div class="relative">
                            <select name="user_id" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-bold text-xs outline-none focus:border-blue-500 transition-all uppercase appearance-none">
                                <option value="">Sélectionner...</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Période (Mois/Année)</label>
                        <input type="month" name="month" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-bold text-xs outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Montant Net (FCFA)</label>
                        <div class="relative">
                            <input type="number" name="amount" placeholder="Ex: 500000" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-bold text-xs outline-none focus:border-blue-500 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 font-black text-slate-400 text-[10px]">FCFA</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Bulletin PDF</label>
                        <div class="flex items-center gap-4 p-2 bg-slate-50 border border-slate-200 rounded-xl">
                            <label class="cursor-pointer px-4 py-2 bg-blue-600 text-white font-black text-[9px] uppercase rounded-lg hover:bg-blue-700 transition-all">
                                Choisir
                                <input type="file" name="pdf" class="hidden" accept="application/pdf" id="pdf-input">
                            </label>
                            <span id="file-name" class="text-[9px] text-slate-500 font-black truncate uppercase">Aucun fichier...</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-blue-600 text-white font-black uppercase tracking-widest text-[10px] rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all transform active:scale-95">
                        Confirmer l'envoi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('pdf-input').addEventListener('change', function(e){
        document.getElementById('file-name').textContent = e.target.files[0] ? e.target.files[0].name : "Aucun fichier...";
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/pages/admin/paie.blade.php ENDPATH**/ ?>