

<?php $__env->startSection('inner-content'); ?>
<div class="space-y-6 min-h-screen" style="font-family: 'Inter', sans-serif; padding: 10px;">
    
    <?php if(auth()->user()->isAdmin()): ?>
        
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
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Arrivée</th>
                            <th class="p-5 text-[10px] font-bold text-slate-500 uppercase text-center">Départ</th>
                            <th class="p-5 text-[10px] font-bold text-blue-600 uppercase text-center bg-blue-50/30">Total Jour</th>
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
                                    <div class="text-[14px] font-semibold text-slate-800"><?php echo e($row->user->name); ?></div>
                                </td>
                                <td class="p-5 text-center text-[11px] font-medium text-slate-700"><?php echo e($row->heure_entree ?? '--:--'); ?></td>
                                <td class="p-5 text-center text-[11px] font-medium text-slate-700"><?php echo e($row->heure_sortie ?? '--:--'); ?></td>
                                <td class="p-5 text-center bg-blue-50/30 font-black text-blue-700 text-[11px]">
                                    <?php echo e($row->total_heures > 0 ? number_format($row->total_heures, 2).'h' : '--'); ?>

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
        </div>

    <?php else: ?>
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b-4 border-slate-100 pb-4">
            <h2 class="text-slate-900 font-black text-2xl uppercase tracking-[0.3em]">Pointage — Aujourd'hui</h2>
            <div class="text-sm text-slate-500 mt-2 md:mt-0 font-bold uppercase tracking-widest">
                <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?> | <span class="text-blue-600"><?php echo e($now->format('H:i')); ?></span>
            </div>
        </div>

        <?php
            $user_ip = request()->ip();
            $local_ips = ['172.20.10.2', '127.0.0.1', '::1', '::ffff:127.0.0.1'];
            $est_au_bureau = in_array($user_ip, $local_ips, true);
            if (env('REQUIRE_OFFICE_WIFI') === 'false' || env('REQUIRE_OFFICE_WIFI') === false) {
                $est_au_bureau = true;
            }
        ?>

        <div class="mt-8 max-w-2xl mx-auto">
            <div class="bg-white p-8 rounded-[30px] text-center border border-slate-200/50 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
                
                <h3 class="text-xs text-slate-400 font-black uppercase mb-6 tracking-[0.2em] relative z-10">Statut de la journée</h3>

                <?php if(!$presence || !$presence->heure_matin): ?>
                    <div class="py-6">
                        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <p class="text-slate-500 text-sm mb-6 font-medium">Prêt à commencer votre service ?</p>
                        
                        
                        <form action="<?php echo e(route('presences.store')); ?>" method="POST" id="form-presence">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="latitude" id="lat">
                            <input type="hidden" name="longitude" id="long">
                            
                            <?php if($est_au_bureau): ?>
                                <button type="button" onclick="recordLocation(this)" class="w-full bg-slate-900 text-white px-8 py-4 rounded-2xl font-black shadow-2xl hover:scale-[1.02] transition-transform uppercase tracking-widest">
                                    Enregistrer mon Arrivée
                                </button>
                            <?php else: ?>
                                <button type="button" disabled class="w-full bg-slate-50 text-slate-300 border-2 border-dashed border-slate-200 px-8 py-4 rounded-2xl font-black uppercase tracking-widest cursor-not-allowed">
                                    <i class="fas fa-wifi mr-2"></i> Wi-Fi Bureau Requis
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>

                <?php elseif(!$presence->heure_soir): ?>
                    <div class="py-6">
                        <div class="text-slate-900 font-black text-4xl mb-2"><?php echo e(\Carbon\Carbon::parse($presence->heure_matin)->format('H:i')); ?></div>
                        <div class="text-[10px] text-emerald-500 font-black uppercase tracking-widest mb-8">Heure d'arrivée validée</div>
                        
                        
                        <form action="<?php echo e(route('presences.store')); ?>" method="POST" id="form-presence">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="latitude" id="lat">
                            <input type="hidden" name="longitude" id="long">

                            <?php if($est_au_bureau): ?>
                                <button type="button" onclick="recordLocation(this)" class="w-full bg-rose-600 text-white px-8 py-4 rounded-2xl font-black shadow-lg hover:bg-rose-700 transition-colors uppercase tracking-widest">
                                    Pointer ma Sortie
                                </button>
                            <?php else: ?>
                                <button type="button" disabled class="w-full bg-slate-50 text-slate-300 border-2 border-dashed border-slate-200 px-8 py-4 rounded-2xl font-black uppercase tracking-widest">
                                    Wi-Fi Bureau Requis
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>

                <?php else: ?>
                    <div class="py-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="text-blue-600 font-black text-5xl mb-2"><?php echo e($presence->total_heures); ?>h</div>
                        <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-4">Temps de travail total</div>
                        
                        <div class="flex justify-center gap-8 text-[11px] font-bold uppercase text-slate-600 border-t border-slate-200 pt-4 mx-6">
                            <div>Arrivée: <span class="text-slate-900"><?php echo e(\Carbon\Carbon::parse($presence->heure_matin)->format('H:i')); ?></span></div>
                            <div>Départ: <span class="text-slate-900"><?php echo e(\Carbon\Carbon::parse($presence->heure_soir)->format('H:i')); ?></span></div>
                        </div>
                    </div>
                    <div class="mt-6 text-[10px] text-emerald-500 font-black uppercase tracking-widest">
                         ✓ Journée complétée avec succès
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>


<script>
function recordLocation(button) {
    const form = button.closest('form');
    // On change le style du bouton pour montrer qu'on travaille
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Localisation...';
    button.disabled = true;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('long').value = position.coords.longitude;
                form.submit();
            },
            function(error) {
                // Si l'utilisateur refuse ou erreur, on prévient mais on peut laisser pointer (optionnel)
                console.error("Erreur GPS:", error.message);
                alert("La localisation est requise pour valider le pointage. Veuillez l'activer.");
                button.innerHTML = 'Réessayer le pointage';
                button.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 5000 }
        );
    } else {
        alert("La géolocalisation n'est pas supportée par votre navigateur.");
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/presences/index.blade.php ENDPATH**/ ?>