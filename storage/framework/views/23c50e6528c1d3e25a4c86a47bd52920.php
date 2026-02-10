<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'text', 'name' => null, 'value' => null, 'placeholder' => '', 'required' => false]));

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

foreach (array_filter((['type' => 'text', 'name' => null, 'value' => null, 'placeholder' => '', 'required' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<input 
    type="<?php echo e($type); ?>"
    name="<?php echo e($name); ?>"
    value="<?php echo e($value); ?>"
    placeholder="<?php echo e($placeholder); ?>"
    <?php echo e($required ? 'required' : ''); ?>

    <?php echo e($attributes->merge(['class' => 'block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none'])); ?>

 />
<?php /**PATH C:\Users\LENOVO\empreinte\resources\views/components/ui/input.blade.php ENDPATH**/ ?>