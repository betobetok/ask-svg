<?php if (isset($component)) { $__componentOriginal394a2520314c0127711358f2bb924fd6ca728f60 = $component; } ?>
<?php $component = $__env->getContainer()->make(ASK\Svg\Components\Icon::class, ['name' => 'camera']); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'icon icon-lg','data-foo' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal394a2520314c0127711358f2bb924fd6ca728f60)): ?>
<?php $component = $__componentOriginal394a2520314c0127711358f2bb924fd6ca728f60; ?>
<?php unset($__componentOriginal394a2520314c0127711358f2bb924fd6ca728f60); ?>
<?php endif; ?><?php /**PATH /tmp/laravel-bladenlS1w8.blade.php ENDPATH**/ ?>