<?php if (isset($component)) { $__componentOriginal779f02523774900e08811a4bd172ab9aa81abe57 = $component; } ?>
<?php $component = $__env->getContainer()->make(BladeUI\Icons\Components\Icon::class, ['name' => 'zondicon-flag']); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal779f02523774900e08811a4bd172ab9aa81abe57)): ?>
<?php $component = $__componentOriginal779f02523774900e08811a4bd172ab9aa81abe57; ?>
<?php unset($__componentOriginal779f02523774900e08811a4bd172ab9aa81abe57); ?>
<?php endif; ?><?php /**PATH /tmp/laravel-bladeVYA293.blade.php ENDPATH**/ ?>