

<?php $__env->startSection('inner-content'); ?>
<div class="p-4 md:p-8 space-y-8 bg-[#f8fafc] min-h-screen">
    
    <?php if(session('success')): ?>
        <div id="success-alert" class="flex items-center p-5 bg-white border-2 border-emerald-500 rounded-[20px] shadow-sm animate-bounce-short">
            <div class="bg-emerald-500 text-white p-2 rounded-lg mr-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-[11px] font-black uppercase text-slate-900 tracking-widest"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tighter">
            Documents <span class="text-slate-300 ml-2">Diffusés</span>
        </h1>
    </div>
            <?php
                $user = auth()->user();
                $docs = collect();
                $users = collect();
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('documents')) {
                        $docs = \App\Models\Document::where('sender_id', $user->id)->with('recipient')->orderByDesc('created_at')->get();
                    }
                    $users = \App\Models\User::where('id', '!=', $user->id)->orderBy('name')->get();
                } catch (Exception $e) {
                    $docs = collect();
                    $users = collect();
                }
            ?>

            
            <div class="mb-2 text-xs text-slate-400">Destinataires trouvés : <?php echo e($users->count()); ?></div>
            <div class="mb-3 text-xs text-slate-300"><?php echo e($users->pluck('name')->join(', ')); ?></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="bg-white p-6 md:p-8 border border-slate-200 rounded-[20px] shadow-sm">
            <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-8">Nouveau transfert</h2>
            
            <form id="uploadForm" method="POST" action="<?php echo e(route('documents.store')); ?>" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Titre du document</label>
                    <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm font-bold text-slate-900 outline-none focus:bg-white focus:ring-2 ring-blue-500 transition-all" placeholder="EX: CONTRAT_PROJET_X">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Destinataire</label>
                    <select name="recipient_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm font-bold text-slate-900 outline-none focus:bg-white focus:ring-2 ring-blue-500 appearance-none cursor-pointer">
                        <option value="">🚀 TOUT LE MONDE</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u->id); ?>">👤 <?php echo e(strtoupper($u->name)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Fichier</label>
                    <div class="relative border-2 border-dashed border-slate-100 rounded-xl p-6 text-center hover:bg-slate-50 transition-all group">
                        <input type="file" name="document" required class="absolute inset-0 opacity-0 cursor-pointer" onchange="updateFileLabel(this)">
                        <p id="file-label" class="text-[10px] font-black text-slate-400 uppercase group-hover:text-blue-600 transition-colors">Choisir sur l'ordinateur</p>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-slate-900 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-xl py-5 shadow-lg hover:bg-blue-600 transition-all flex items-center justify-center">
                    <span id="btnText">Lancer l'envoi</span>
                    <div id="loader" class="hidden ml-3 w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[20px] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50">
                <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Fichiers récemment partis</h3>
            </div>

            <div class="divide-y divide-slate-50">
                <?php $__empty_1 = true; $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/50 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-900 uppercase leading-none"><?php echo e($d->title); ?></h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">À: <span class="text-blue-500"><?php echo e(optional($d->recipient)->name ?? 'Tous'); ?></span></p>
                            </div>
                        </div>
                                <?php if(!empty($d->file_path)): ?>
                                    <div class="text-xs text-slate-400">Fichier : <?php echo e(basename($d->file_path)); ?></div>
                                <?php endif; ?>
                                <a href="<?php echo e(asset('storage/' . $d->file_path)); ?>" target="_blank" class="px-5 py-2 bg-white border border-slate-200 text-slate-900 text-[9px] font-black uppercase rounded-lg hover:bg-slate-900 hover:text-white transition-all shadow-sm">Ouvrir</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-16 text-center">
                        <p class="text-[10px] font-black text-slate-300 uppercase italic">Aucun envoi enregistré</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Label du fichier
    function updateFileLabel(input) {
        const label = document.getElementById('file-label');
        if (input.files.length > 0) {
            label.innerText = "✓ " + input.files[0].name;
            label.classList.replace('text-slate-400', 'text-blue-600');
        }
    }

    // Effet de chargement
    document.getElementById('uploadForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const loader = document.getElementById('loader');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        text.innerText = "TRANSMISSION...";
        loader.classList.remove('hidden');
    };

    // Suppression auto de l'alerte
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if(alert) {
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 600);
        }
    }, 3500);
</script>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .animate-bounce-short { animation: bounce-short 1s ease-in-out infinite; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/pages/documents/envoyes.blade.php ENDPATH**/ ?>