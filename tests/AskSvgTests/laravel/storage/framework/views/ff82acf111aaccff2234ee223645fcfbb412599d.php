<?php if (isset($component)) { $__componentOriginal5518e5dbd0d2f249382fceb35002484a4efcccb8 = $component; } ?>
<?php $component = $__env->getContainer()->make(ASK\Svg\Components\SvgComponent::class, []); ?>
<?php $component->withName('icon-camera'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'icon icon-lg','data-foo' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5518e5dbd0d2f249382fceb35002484a4efcccb8)): ?>
<?php $component = $__componentOriginal5518e5dbd0d2f249382fceb35002484a4efcccb8; ?>
<?php unset($__componentOriginal5518e5dbd0d2f249382fceb35002484a4efcccb8); ?>
<?php endif; ?><?php /**PATH /tmp/laravel-bladePL2sdk.blade.php ENDPATH**/ ?>