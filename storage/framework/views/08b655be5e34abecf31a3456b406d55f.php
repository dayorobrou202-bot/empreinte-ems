


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<?php $__env->startSection('inner-content'); ?>
<div class="space-y-6" style="font-family: 'Inter', sans-serif; padding: 10px;">
    
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                HISTORIQUE <span class="text-blue-600">PRÉSENCES</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Flux de présence administratif • YA CONSULTING</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="<?php echo e(route('admin.presences.export', request()->query())); ?>" 
               class="flex-1 md:flex-none text-center bg-white text-slate-900 px-6 py-2.5 font-bold text-[11px] uppercase border border-slate-300 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                Exporter CSV
            </a>
        </div>
    </div>

    
    <?php if(!auth()->user()->isAdmin()): ?>
    <div class="max-w-md mx-auto my-10">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/50 text-center">
            <?php if(!$presence || !$presence->heure_matin): ?>
                <div class="space-y-6">
                    <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                    </div>
                    <form action="<?php echo e(route('presences.store')); ?>" method="POST" id="form-presence">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="long">
                        <button type="button" onclick="recordLocation(this)" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg active:scale-95">
                            Enregistrer mon Arrivée
                        </button>
                    </form>
                </div>
            <?php elseif(!$presence->heure_soir): ?>
                <div class="space-y-6">
                    <div class="text-5xl font-black text-slate-900 tracking-tighter"><?php echo e(\Carbon\Carbon::parse($presence->heure_matin)->format('H:i')); ?></div>
                    <form action="<?php echo e(route('presences.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="long">
                        <button type="button" onclick="recordLocation(this)" class="w-full bg-rose-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-rose-700 transition-all shadow-lg active:scale-95">
                            Pointer ma Sortie
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="py-10">
                    <div class="text-blue-600 font-black text-6xl mb-2"><?php echo e($presence->total_heures); ?>h</div>
                    <p class="text-emerald-500 font-black text-[10px] uppercase tracking-widest">Service Terminé ✓</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(auth()->user()->isAdmin()): ?>
    <div style="background:#ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow:hidden; shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th class="p-5 text-[10px] font-bold text-slate-500 uppercase">Collaborateur</th>
                        <th class="p-5 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">Arrivée</th>
                        <th class="p-5 text-center text-[10px] font-bold text-blue-600 uppercase bg-blue-50/30">Total</th>
                        <th class="p-5 text-center text-[10px] font-bold text-slate-500 uppercase">Zone</th>
                        <th class="p-5 text-right text-[10px] font-bold text-slate-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50"> 
                    <?php $__currentLoopData = $presenceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $bureauLat = 5.36532; $bureauLong = -3.95763;
                            $distM = null; $estHors = false;
                            if($row->latitude_entree && $row->longitude_entree) {
                                $theta = $row->longitude_entree - $bureauLong;
                                $d = sin(deg2rad($row->latitude_entree)) * sin(deg2rad($bureauLat)) + cos(deg2rad($row->latitude_entree)) * cos(deg2rad($bureauLat)) * cos(deg2rad($theta));
                                $distM = acos($d) * 60 * 1.1515 * 1.609344 * 1000;
                                if($distM > 50) $estHors = true;
                            }
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-5 flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 text-blue-600 flex items-center justify-center font-bold rounded-xl border border-slate-200 uppercase text-xs"><?php echo e(substr($row->user->name, 0, 1)); ?></div>
                                <span class="font-bold text-slate-800 text-sm"><?php echo e($row->user->name); ?></span>
                            </td>
                            <td class="p-5 text-center text-sm font-black <?php echo e($estHors ? 'text-rose-600' : 'text-slate-700'); ?>">
                                <?php echo e($row->heure_entree ?? '--:--'); ?>

                            </td>
                            <td class="p-5 text-center bg-blue-50/30">
                                <span class="text-sm font-black text-blue-700"><?php echo e($row->total_heures > 0 ? number_format($row->total_heures, 2).'h' : '--'); ?></span>
                            </td>
                            <td class="p-5 text-center">
                                <span class="px-3 py-1 text-[9px] font-black rounded-full border <?php echo e($estHors ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'); ?>">
                                    <?php echo e($estHors ? 'HORS ZONE' : 'AU BUREAU'); ?>

                                </span>
                            </td>
                            <td class="p-5 text-right">
                                <?php if($estHors): ?>
                                    <span class="px-3 py-1 bg-rose-600 text-white text-[9px] font-black rounded-lg animate-pulse uppercase shadow-md shadow-rose-200">⚠️ ABSENT</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 <?php echo e($row->present ? 'bg-emerald-500 text-white shadow-emerald-100' : 'bg-slate-100 text-slate-400'); ?> text-[9px] font-black rounded-lg uppercase shadow-md">
                                        <?php echo e($row->present ? 'PRÉSENT' : 'ABSENT'); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>


<script>
const OFFICE_LAT = 5.36532, OFFICE_LONG = -3.95763, MAX_DIST = 50;

function calcDist(lat1, lon1, lat2, lon2) {
    const R = 6371e3;
    const φ1 = lat1 * Math.PI/180, φ2 = lat2 * Math.PI/180;
    const Δφ = (lat2-lat1) * Math.PI/180, Δλ = (lon2-lon1) * Math.PI/180;
    const a = Math.sin(Δφ/2)**2 + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2)**2;
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

function recordLocation(btn) {
    const form = btn.closest('form'), txt = btn.innerHTML;
    btn.innerHTML = '<span class="animate-pulse">Vérification Zone...</span>'; 
    btn.disabled = true;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const d = calcDist(pos.coords.latitude, pos.coords.longitude, OFFICE_LAT, OFFICE_LONG);
                
                if (d > MAX_DIST) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Accès Refusé',
                        html: `Vous êtes à <b>${Math.round(d)}m</b> de YA CONSULTING.<br><small>Veuillez vous rapprocher pour pointer.</small>`,
                        confirmButtonText: 'D\'accord',
                        confirmButtonColor: '#0f172a'
                    });
                    btn.innerHTML = txt; 
                    btn.disabled = false;
                } else {
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('long').value = pos.coords.longitude;
                    form.submit();
                }
            },
            () => { 
                Swal.fire({ icon: 'warning', title: 'GPS Inactif', text: 'Veuillez activer votre localisation.' });
                btn.innerHTML = txt; btn.disabled = false; 
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\empreinte\resources\views/presences/index.blade.php ENDPATH**/ ?>