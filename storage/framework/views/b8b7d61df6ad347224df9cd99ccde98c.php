<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'button', 'variant' => 'primary']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['type' => 'button', 'variant' => 'primary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $base = 'inline-flex items-center justify-center px-4 py-2 font-black text-xs uppercase rounded-xl transition-all';
    $styles = $variant === 'primary' ? $base . ' bg-blue-600 text-white hover:bg-blue-700' : $base . ' bg-white text-slate-800 border border-slate-200 hover:bg-slate-50';
?>
<button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $styles])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH C:\Users\LENOVO\empreinte\resources\views/components/ui/button.blade.php ENDPATH**/ ?>